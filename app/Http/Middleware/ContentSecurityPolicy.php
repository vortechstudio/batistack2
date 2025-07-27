<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Récupérer la configuration CSP
        $config = config('csp');
        $directives = $config['directives'];

        // En mode développement, ajouter les overrides
        if ($config['development_mode'] && app()->environment('local')) {
            foreach ($config['development_overrides'] as $directive => $override) {
                if (isset($directives[$directive])) {
                    $directives[$directive] .= ' '.$override;
                }
            }
        }

        // Construire la politique CSP
        $cspParts = [];
        foreach ($directives as $directive => $value) {
            $cspParts[] = $directive.' '.$value;
        }

        // Ajouter upgrade-insecure-requests si pas en développement
        if (! app()->environment('local')) {
            $cspParts[] = 'upgrade-insecure-requests';
        }

        $cspHeader = implode('; ', $cspParts);

        // Définir l'en-tête CSP (report-only ou normal)
        $headerName = $config['report_only'] ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
        $response->headers->set($headerName, $cspHeader);

        // Ajouter l'URI de rapport si configuré
        if ($config['report_uri']) {
            $cspHeader .= '; report-uri '.$config['report_uri'];
            $response->headers->set($headerName, $cspHeader);
        }

        // Ajouter les en-têtes de sécurité supplémentaires
        foreach ($config['additional_headers'] as $header => $value) {
            $response->headers->set($header, $value);
        }

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Core\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class RedirectByRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur n'est pas authentifié, continuer
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $currentRoute = $request->route()->getName();

        // Définir les routes autorisées par rôle
        $allowedRoutes = $this->getAllowedRoutesByRole($user->role);

        // Si la route actuelle n'est pas autorisée pour ce rôle
        if (!in_array($currentRoute, $allowedRoutes) && !$this->isCommonRoute($currentRoute)) {
            $defaultRoute = $this->getDefaultRouteByRole($user->role);
            return redirect()->route($defaultRoute);
        }

        return $next($request);
    }

    /**
     * Obtenir les routes autorisées par rôle
     */
    private function getAllowedRoutesByRole(UserRole $role): array
    {
        return match ($role) {
            UserRole::SALARIE => [
                'portail.salarie.dashboard',
                'portail.salarie.documents',
                'portail.salarie.documents.signed',
                'portail.salarie.bank',
                'portail.salarie.frais',
                'portail.salarie.frais.show',
            ],
            UserRole::ADMINISTRATEUR => [
                'dashboard',
                'humans.dashboard',
                'humans.salaries.index',
                'humans.salaries.view',
                'humans.salaries.transmission',
                'humans.conges',
                'humans.frais',
                'humans.frais.show',
                'humans.config.index',
                'chantiers.dashboard',
                'chantiers.view',
                'tiers.dashboard',
                'tiers.supply.list',
                'tiers.customers.list',
                'settings.profile',
                'settings.password',
                'settings.appearance',
                'settings.company',
                'settings.app',
                'settings.pcg',
            ],
            UserRole::COMPTABILITE => [
                'dashboard',
                'tiers.dashboard',
                'tiers.supply.list',
                'tiers.customers.list',
                'settings.profile',
                'settings.password',
                'settings.pcg',
            ],
            UserRole::COUNTERMASTER => [
                'dashboard',
                'chantiers.dashboard',
                'chantiers.view',
                'settings.profile',
                'settings.password',
            ],
            UserRole::CLIENT => [
                'dashboard',
                'settings.profile',
                'settings.password',
            ],
            UserRole::FOURNISSEUR => [
                'dashboard',
                'settings.profile',
                'settings.password',
            ],
            default => ['dashboard'],
        };
    }

    /**
     * Obtenir la route par défaut par rôle
     */
    private function getDefaultRouteByRole(UserRole $role): string
    {
        return match ($role) {
            UserRole::SALARIE => 'portail.salarie.dashboard',
            UserRole::ADMINISTRATEUR => 'dashboard',
            UserRole::COMPTABILITE => 'dashboard',
            UserRole::COUNTERMASTER => 'chantiers.dashboard',
            UserRole::CLIENT => 'dashboard',
            UserRole::FOURNISSEUR => 'dashboard',
            default => 'dashboard',
        };
    }

    /**
     * Vérifier si c'est une route commune (accessible à tous)
     */
    private function isCommonRoute(string $routeName): bool
    {
        $commonRoutes = [
            'logout',
            'notifications',
            'settings.profile',
            'settings.password',
            'settings.appearance',
            'verification.notice',
            'verification.verify',
            'password.confirm',
        ];

        return in_array($routeName, $commonRoutes);
    }
}

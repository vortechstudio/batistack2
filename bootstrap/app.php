<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Ajouter le middleware de sécurité CSP globalement
        $middleware->web(append: [
            App\Http\Middleware\ContentSecurityPolicy::class,
            App\Http\Middleware\RedirectByRole::class, // Nouveau middleware
        ]);

        // Ou l'ajouter comme middleware nommé
        $middleware->alias([
            'role.redirect' => App\Http\Middleware\RedirectByRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

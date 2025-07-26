<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Configuration
    |--------------------------------------------------------------------------
    |
    | Cette configuration permet de personnaliser les directives CSP
    | selon l'environnement et les besoins spécifiques de l'application.
    |
    */

    'report_only' => env('CSP_REPORT_ONLY', false),
    'development_mode' => env('CSP_DEVELOPMENT_MODE', false),
    'report_uri' => env('CSP_REPORT_URI', null),

    /*
    |--------------------------------------------------------------------------
    | CSP Directives
    |--------------------------------------------------------------------------
    |
    | Définition des directives CSP par défaut. Ces valeurs peuvent être
    | surchargées via les variables d'environnement.
    |
    */

    'directives' => [
        'default-src' => env('CSP_DEFAULT_SRC', "'self'"),
        'script-src' => env('CSP_SCRIPT_SRC', "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com"),
        'style-src' => env('CSP_STYLE_SRC', "'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net"),
        'font-src' => env('CSP_FONT_SRC', "'self' https://fonts.gstatic.com data:"),
        'img-src' => env('CSP_IMG_SRC', "'self' data: https: blob:"),
        'connect-src' => env('CSP_CONNECT_SRC', "'self' ws: wss:"),
        'media-src' => env('CSP_MEDIA_SRC', "'self'"),
        'object-src' => env('CSP_OBJECT_SRC', "'none'"),
        'frame-src' => env('CSP_FRAME_SRC', "'self'"),
        'base-uri' => env('CSP_BASE_URI', "'self'"),
        'form-action' => env('CSP_FORM_ACTION', "'self'"),
        'frame-ancestors' => env('CSP_FRAME_ANCESTORS', "'self'"),
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Security Headers
    |--------------------------------------------------------------------------
    |
    | Configuration des en-têtes de sécurité supplémentaires.
    |
    */

    'additional_headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Overrides
    |--------------------------------------------------------------------------
    |
    | Ajustements spécifiques pour l'environnement de développement.
    |
    */

    'development_overrides' => [
        'connect-src' => 'http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*',
        'script-src' => 'http://localhost:* http://127.0.0.1:*',
        'style-src' => 'http://localhost:* http://127.0.0.1:*',
    ],
];
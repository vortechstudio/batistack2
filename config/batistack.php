<?php

declare(strict_types=1);

return [
    'unit_poids' => [
        'slug' => 'kilogram',
        'name' => 'Kilogramme',
        'code' => 'Kg',
    ],
    'unit_volume' => [
        'slug' => 'cube_meter',
        'name' => 'Mètres cubes',
        'code' => 'M&#13221;',
    ],
    'domain' => env('APP_DOMAIN'),
    'version' => env('APP_VERSION'),
    'imap' => [
        'host' => env('IMAP_HOST'),
        'port' => env('IMAP_PORT'),
        'default' => [
            'username' => env('IMAP_USERNAME'),
            'password' => env('IMAP_PASSWORD'),
        ],
        'comptabilite' => [
            'username' => env('IMAP_USERNAME_COMPTA'),
            'password' => env('IMAP_PASSWORD_COMPTA'),
        ],
    ],
];

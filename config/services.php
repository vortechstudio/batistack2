<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'bridge' => [
        'client_id' => env('BRIDGE_CLIENT_ID'),
        'client_secret' => env('BRIDGE_CLIENT_SECRET'),
        'endpoint' => 'https://api.bridgeapi.io/v3/',
        'version' => '2025-01-15',
    ],

    'siren' => [
        'api_key' => env('SIREN_API_KEY'),
    ],

    'twillio' => [
        'sid' => env('TWILIO_AUTH_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
    ],

    'powens' => [
        "api_endpoint" => env('POWENS_API_URL'),
        "client_id" => env('POWENS_CLIENT_ID'),
        "client_secret" => env('POWENS_CLIENT_SECRET'),
    ],

    'tesseract' => [
        'bin_path' => env('TESSERACT_BIN_PATH', 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'),
        'lang' => env('TESSERACT_LANG', 'fra'),
    ]

];

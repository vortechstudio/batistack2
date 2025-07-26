<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    // Bugsnag\BugsnagLaravel\BugsnagServiceProvider::class,
    QCod\AppSettings\AppSettingsServiceProvider::class,
    Webklex\IMAP\Providers\LaravelServiceProvider::class,
];

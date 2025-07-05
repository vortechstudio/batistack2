<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    Bugsnag\BugsnagLaravel\BugsnagServiceProvider::class,
    Webklex\IMAP\Providers\LaravelServiceProvider::class,
    \QCod\AppSettings\AppSettingsServiceProvider::class,
];

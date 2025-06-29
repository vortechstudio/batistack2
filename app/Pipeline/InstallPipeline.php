<?php

declare(strict_types=1);

namespace App\Pipeline;

use Closure;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Facades\Artisan;

final class InstallPipeline implements Pipeline
{
    public function handle($payload, Closure $next): void
    {
        \Artisan::call('install:cities');
        Artisan::call('install:country');
        Artisan::call('install:pcg');
        $next($payload);
    }

    /**
     * {@inheritDoc}
     */
    public function send($passable): void
    {
        // TODO: Implement send() method.
    }

    /**
     * {@inheritDoc}
     */
    public function through($pipes): void
    {
        // TODO: Implement through() method.
    }

    /**
     * {@inheritDoc}
     */
    public function via($method): void
    {
        // TODO: Implement via() method.
    }

    /**
     * {@inheritDoc}
     */
    public function then(Closure $destination): void
    {
        // TODO: Implement then() method.
    }
}

<?php

namespace App\Pipeline;

use Closure;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Facades\Artisan;

class InstallPipeline implements Pipeline
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
    public function send($passable)
    {
        // TODO: Implement send() method.
    }

    /**
     * {@inheritDoc}
     */
    public function through($pipes)
    {
        // TODO: Implement through() method.
    }

    /**
     * {@inheritDoc}
     */
    public function via($method)
    {
        // TODO: Implement via() method.
    }

    /**
     * {@inheritDoc}
     */
    public function then(Closure $destination)
    {
        // TODO: Implement then() method.
    }
}

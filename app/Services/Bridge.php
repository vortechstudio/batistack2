<?php

namespace App\Services;

class Bridge
{
    private string $client_id;
    private string $client_secret;

    public function __construct()
    {
        $this->client_id = config('services.bridge.client_id');
        $this->client_secret = config('services.bridge.client_secret');
    }
}

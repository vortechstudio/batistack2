<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YousignService
{
    protected string $url;
    protected string $apiKey;

    public function __construct()
    {
        $this->url = config('services.yousign.api_url');
        $this->apiKey = config('services.yousign.api_key');
    }

    protected function headers(): array
    {
        return [
            'Autorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json'
        ];
    }

    public function createProcedure(array $data)
    {
        return Http::withoutVerifying()
            ->withHeaders($this->headers())
            ->post("{$this->url}/procedures", $data)
            ->json();
    }

    public function uploadFile(string $filePath, string $fileName)
    {
        return Http::withoutVerifying()
            ->withToken($this->apiKey)
            ->attach('file', file_get_contents($filePath), $fileName)
            ->post("{$this->url}/files")
            ->json();
    }

    public function createSignature(array $data)
    {
        return Http::withoutVerifying()
            ->withHeaders($this->headers())
            ->post("{$this->url}/signature_requests", $data)
            ->json();
    }
}

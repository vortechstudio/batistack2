<?php

namespace App\Services;

use Exception;
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

    public function createSignatureRequest(array $data)
    {
        try {
            return Http::withoutVerifying()
                ->withHeaders($this->headers())
                ->post("{$this->url}/signature_requests", $data)
                ->json();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function createSignatureRequestDocument(string $uuid, array $data, string $filePath, string $fileName)
    {
        return Http::withoutVerifying()
            ->withHeaders($this->headers())
            ->attach('file', file_get_contents($filePath), $fileName)
            ->post("{$this->url}/signature_requests/{$uuid}/documents", $data)
            ->json();
    }

    public function addSignerToSignatureRequest(string $uuid, array $data)
    {
        return Http::withoutVerifying()
            ->withHeaders($this->headers())
            ->post("{$this->url}/signature_requests/{$uuid}/signers", $data)
            ->json();
    }

    public function activateSignatureRequest(string $uuid)
    {
        return Http::withoutVerifying()
            ->withHeaders($this->headers())
            ->post("{$this->url}/signature_requests/{$uuid}/activate")
            ->json();
    }
}

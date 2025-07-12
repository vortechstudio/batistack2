<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Powens
{
    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $baseUrl
     */
    public function __construct(
        protected string|null $client_id = null,
        protected string|null $client_secret = null,
        protected string|null $api_endpoint = null,
    )
    {
        $this->client_id = config('services.powens.client_id');
        $this->client_secret = config('services.powens.client_secret');
        $this->api_endpoint = config('services.powens.api_endpoint');
    }

    public function get(
        string $folder,
        ?array $data = null,
        ?string $withToken = null,
    )
    {
        try{
            if ($withToken !== null && $withToken !== '' && $withToken !== '0')
            {
                $request = Http::withoutVerifying()
                ->withHeaders([
                    'autorisation' => 'Bearer '.$withToken,
                    'Client-Id' => $this->client_id,
                    'Client-Secret' => $this->client_secret,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])
                ->get($this->api_endpoint.$folder, $data)
                ->json();
            } else {
                $request = Http::withoutVerifying()
                ->withHeaders([
                    'Client-Id' => $this->client_id,
                    'Client-Secret' => $this->client_secret,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])
                ->get($this->api_endpoint.$folder, $data)
                ->json();
            }

            return collect($request)->toArray();
        }catch(Exception $exception) {
            Log::emergency($exception);
            return null;
        }
    }


}

<?php

declare(strict_types=1);

namespace App\Services;

use Http;
use Illuminate\Http\Client\ConnectionException;

final class Siren
{
    /**
     * @throws ConnectionException
     */
    public function call(string $siren, string $type = 'info'): mixed
    {
        $request = Http::withoutVerifying()
            ->withHeaders([
                'X-INSEE-Api-Key-Integration' => config('services.siren_api.key'),
            ])
            ->get('https://api.insee.fr/api-sirene/3.11/siren/'.$siren);

        if ($request->getStatusCode() === 404) {
            return false;
        }
        if ($type === 'info') {
            return $request->json()['uniteLegale'];
        }

        return true;

    }
}

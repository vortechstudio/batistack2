<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class OpenIban
{
    private string $endpoint = 'https://openiban.com/validate/';

    public function validate(string $iban): bool
    {
        $call = Http::get($this->endpoint.$iban)
            ->json();

        if ($call['valid'] === true) {
            return true;
        }

        return false;
    }

    public function info(string $iban): array
    {
        $call = Http::get($this->endpoint.$iban)
            ->json();

        return $call;
    }
}

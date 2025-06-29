<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Tiers\Tiers;

final class Helpers
{
    public static function eur(string|int|float $number): string
    {
        return number_format($number, 2, ',', ' ').' €';
    }

    public static function generatePassword(int $length = 12): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789*%_-)=';

        return mb_substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
    }

    public static function generateCodeTiers(string $type): string
    {
        if ($type === 'f') {
            $latest = Tiers::where('nature', 'fournisseur')->orderBy('id', 'desc')->first();

            return $latest ? 'SUP'.now()->year.'-00'.$latest->id + 1 : 'SUP'.now()->year.'-001';
        }
        $latest = Tiers::where('nature', 'client')->orderBy('id', 'desc')->first();

        return $latest ? 'CUS'.now()->year.'-00'.$latest->id + 1 : 'CUS'.now()->year.'-001';
    }
}

<?php

namespace App\Helpers;

use App\Models\Tiers\Tiers;

class Helpers
{
    public static function eur(string|int|float $number): string
    {
        return number_format($number, 2, ',', ' ')." €";
    }

    public static function generatePassword(int $length = 12): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789*%_-)=';
        return substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
    }

    public static function generateCodeTiers(string $type): string
    {
        if ($type === 'f') {
            $latest = Tiers::where('nature', 'fournisseur')->orderBy('id', 'desc')->first();
            if ($latest) {
                $code = "SUP".now()->year.'-00'.$latest->id+1;
            } else {
                $code = "SUP".now()->year.'-001';
            }
        } else {
            $latest = Tiers::where('nature', 'client')->orderBy('id', 'desc')->first();
            if ($latest) {
                $code = "CUS".now()->year.'-00'.$latest->id+1;
            } else {
                $code = "CUS".now()->year.'-001';
            }
        }

        return $code;
    }
}

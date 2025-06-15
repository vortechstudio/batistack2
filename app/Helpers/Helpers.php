<?php

namespace App\Helpers;

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
}

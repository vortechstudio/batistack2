<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Commerce\Commande;
use App\Models\Commerce\Devis;
use App\Models\Commerce\Facture;
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

    public static function generateCodeDevis(): string
    {
        $latest = Devis::orderBy('id', 'desc')->first();
        return $latest ? 'DE'.now()->year.'-00'.$latest->id + 1 : 'DE'.now()->year.'-001';
    }

    public static function generateCodeCommande(): string
    {
        $latest = Commande::orderBy('id', 'desc')->first();
        return $latest ? 'CMD'.now()->year.'-00'.$latest->id + 1 : 'CMD'.now()->year.'-001';
    }

    public static function generateCodeFacture(): string
    {
        $latest = Facture::orderBy('id', 'desc')->first();
        return $latest ? 'FCT'.now()->year.'-00'.$latest->id + 1 : 'FCT'.now()->year.'-001';
    }

    public static function getLastestVersion()
    {
        if (config('app.env') === 'production') {
            return config('batistack.version');
        }

        return 'Dev';

    }
}

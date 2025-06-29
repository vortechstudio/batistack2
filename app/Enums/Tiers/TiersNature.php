<?php

namespace App\Enums\Tiers;

enum TiersNature: string
{
    case Fournisseur = 'fournisseur';
    case Client = 'client';

    public function label()
    {
        return match ($this) {
            self::Fournisseur => 'Fournisseur',
            self::Client => 'Client',
        };
    }

    public function color()
    {
        return match ($this) {
            self::Fournisseur => 'red',
            self::Client => 'green',
        };
    }

    public static function array()
    {
        return [
            'fournisseur',
            'client',
        ];
    }
}

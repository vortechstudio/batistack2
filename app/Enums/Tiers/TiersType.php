<?php

namespace App\Enums\Tiers;

enum TiersType: string
{
    case Administration = 'administration';
    case Autre = 'autre';
    case GrandCompte = 'grand_compte';
    case PMEPMI = 'pme_pmi';
    case Particulier = 'particulier';
    case Tpe = 'tpe';


    public function label()
    {
        return match ($this) {
            self::Administration => 'Administration',
            self::Autre => 'Autre',
            self::GrandCompte => 'Grand Compte',
            self::PMEPMI => 'PME/PMI',
            self::Particulier => 'Particulier',
            self::Tpe => 'Tpe',
        };
    }

    public static function plucks()
    {
        return [
            "administration" => "Administration",
            "autre" => "Autre",
            "grand_compte" => "Grand Compte",
            "pme_pmi" => "PME/PMI",
            "particulier" => "Particulier",
            "tpe" => "TPE",
        ];
    }

    public static function array()
    {
        return [
            'administration',
            'autre',
            'grand_compte',
            'pme_pmi',
            'particulier',
            'tpe'
        ];
    }
}

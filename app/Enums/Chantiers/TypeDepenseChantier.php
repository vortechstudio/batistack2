<?php

declare(strict_types=1);

namespace App\Enums\Chantiers;

enum TypeDepenseChantier: string
{
    case Materiel = 'materiel';
    case Main_Oeuvre = 'main_oeuvre';
    case Sous_Traitance = 'sous_traitance';
    case Transport = 'transport';
    case Frais = 'frais';

    public static function array()
    {
        return [
            'materiel' => 'materiel',
            'main_oeuvre' => 'main_oeuvre',
            'sous_traitance' => 'sous_traitance',
            'transport' => 'transport',
            'frais' => 'frais',
        ];
    }

    public function label()
    {
        return match ($this) {
            self::Materiel => 'Materiel',
            self::Main_Oeuvre => 'Main Oeuvre',
            self::Sous_Traitance => 'Sous Traitance',
            self::Transport => 'Transport',
            self::Frais => 'Frais',
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Enums\Chantiers;

enum TypeDepenseChantier: string
{
    case Materiel = 'materiel';
    case Main_Oeuvre = 'main_oeuvre';
    case Sous_Traitance = 'sous_traitance';
    case Transport = 'transport';

    public static function array()
    {
        return [
            'materiel' => self::Materiel,
            'main_oeuvre' => self::Main_Oeuvre,
            'sous_traitance' => self::Sous_Traitance,
            'transport' => self::Transport,
        ];
    }

    public function label()
    {
        return match ($this) {
            self::Materiel => 'Materiel',
            self::Main_Oeuvre => 'Main Oeuvre',
            self::Sous_Traitance => 'Sous Traitance',
            self::Transport => 'Transport',
        };
    }
}

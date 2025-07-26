<?php

declare(strict_types=1);

namespace App\Enums\Commerce;

enum CategoryFee: string
{
    case CARBURANT = 'carburant';
    case REPAS = 'repas';
    case OUTILS = 'outillages';
    case TRANSPORT = 'transport';
    case AUTRE = 'autre';

    public function label()
    {
        return match ($this) {
            self::CARBURANT => 'Carburant',
            self::REPAS => 'Repas',
            self::OUTILS => 'Outillages',
            self::TRANSPORT => 'Transport',
            self::AUTRE => 'Autre',
        };
    }
}

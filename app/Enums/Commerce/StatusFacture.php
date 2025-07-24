<?php

declare(strict_types=1);

namespace App\Enums\Commerce;

enum StatusFacture: string
{
    case NON_PAYER = 'non_payer';
    case P_PAYER = 'partiellement';
    case PAYER = 'payer';
    case RETARD = 'retard';

    public function label()
    {
        return match ($this) {
            self::NON_PAYER => 'Non payer',
            self::P_PAYER => 'Partiellement payer',
            self::PAYER => 'Payer',
            self::RETARD => 'En retard',
        };
    }

    public function color()
    {
        return match ($this) {
            self::NON_PAYER => 'gray',
            self::P_PAYER => 'blue',
            self::PAYER => 'green',
            self::RETARD => 'red',
            default => 'gray',
        };
    }
}

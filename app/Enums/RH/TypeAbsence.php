<?php

declare(strict_types=1);

namespace App\Enums\RH;

use Filament\Support\Contracts\HasLabel;

enum TypeAbsence: string implements HasLabel
{
    case MALADIE = 'maladie';
    case PAYED = 'payed';
    case RTT = 'rtt';
    case INJUSTIFIED = 'injustified';
    case OTHER = 'other';

    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
            ->toArray();
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MALADIE => 'Congés Maladie',
            self::PAYED => 'Congés Payé',
            self::RTT => 'RTT',
            self::INJUSTIFIED => 'Absence Injustifiée',
            self::OTHER => 'Autre',
        };
    }

    public function label()
    {
        return match ($this) {
            self::MALADIE => 'Congés Maladie',
            self::PAYED => 'Congés Payé',
            self::RTT => 'RTT',
            self::INJUSTIFIED => 'Absence Injustifiée',
            self::OTHER => 'Autre',
        };
    }
}

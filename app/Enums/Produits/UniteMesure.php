<?php

declare(strict_types=1);

namespace App\Enums\Produits;

enum UniteMesure: string
{
    case MILLIMETRE = 'mm';
    case CENTIMETRE = 'cm';
    case DECIMETRE = 'dm';
    case METRE = 'm';
    case METRELINEAIRE = 'ml';
    case METRECARRE = 'm²';

    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($mode) => [$mode->value => $mode->label()])
            ->toArray();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::MILLIMETRE;
    }

    public function label(): string
    {
        return match ($this) {
            self::MILLIMETRE => 'Millimètre (mm)',
            self::CENTIMETRE => 'Centimètre (cm)',
            self::DECIMETRE => 'Décimètre (dm)',
            self::METRE => 'Mètre (m)',
            self::METRELINEAIRE => 'Mètre linéaire (ml)',
            self::METRECARRE => 'Mètre Carré (m²)',
        };
    }

    public function symbol(): string
    {
        return $this->value;
    }
}

<?php

declare(strict_types=1);

namespace App\Enums\Produits;

enum UniteMesure: string
{
    case MILLIMETRE = 'mm';
    case CENTIMETRE = 'cm';
    case DECIMETRE = 'dm';
    case METRE = 'm';
    case MILLILITRE = 'ml';

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
            self::MILLILITRE => 'Millilitre (ml)',
        };
    }

    public function symbol(): string
    {
        return $this->value;
    }
}

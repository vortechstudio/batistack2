<?php

namespace App\Enums\Produits;

enum UnitePoids: string
{
    case MILLIGRAMME = 'mg';
    case GRAMME = 'g';
    case KILOGRAMME = 'kg';
    case TONNE = 't';

    public function label(): string
    {
        return match($this) {
            self::MILLIGRAMME => 'Milligramme (mg)',
            self::GRAMME => 'Gramme (g)',
            self::KILOGRAMME => 'Kilogramme (kg)',
            self::TONNE => 'Tonne (t)',
        };
    }

    public function symbol(): string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::KILOGRAMME;
    }
}

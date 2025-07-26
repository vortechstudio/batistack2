<?php

namespace App\Enums\RH;

enum StatusNoteFrais: string
{
    case BROUILLON = 'brouillon';
    case SOUMISE = 'soumise';
    case VALIDEE = 'validee';
    case REFUSEE = 'refusee';
    case PAYEE = 'payee';

    public function label(): string
    {
        return match ($this) {
            self::BROUILLON => 'Brouillon',
            self::SOUMISE => 'Soumise',
            self::VALIDEE => 'Validée',
            self::REFUSEE => 'Refusée',
            self::PAYEE => 'Payée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BROUILLON => 'gray',
            self::SOUMISE => 'blue',
            self::VALIDEE => 'green',
            self::REFUSEE => 'red',
            self::PAYEE => 'purple',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::BROUILLON => 'heroicon-o-document',
            self::SOUMISE => 'heroicon-o-paper-airplane',
            self::VALIDEE => 'heroicon-o-check-circle',
            self::REFUSEE => 'heroicon-o-x-circle',
            self::PAYEE => 'heroicon-o-banknotes',
        };
    }

    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($status) => [$status->value => $status->label()])
            ->toArray();
    }
}

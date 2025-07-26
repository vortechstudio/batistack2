<?php

declare(strict_types=1);

namespace App\Enums\RH;

enum TypeFrais: string
{
    case TRANSPORT = 'transport';
    case HEBERGEMENT = 'hebergement';
    case RESTAURATION = 'restauration';
    case CARBURANT = 'carburant';
    case PEAGE = 'peage';
    case PARKING = 'parking';
    case MATERIEL = 'materiel';
    case FORMATION = 'formation';
    case COMMUNICATION = 'communication';
    case AUTRE = 'autre';

    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
            ->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::TRANSPORT => 'Transport',
            self::HEBERGEMENT => 'Hébergement',
            self::RESTAURATION => 'Restauration',
            self::CARBURANT => 'Carburant',
            self::PEAGE => 'Péage',
            self::PARKING => 'Parking',
            self::MATERIEL => 'Matériel',
            self::FORMATION => 'Formation',
            self::COMMUNICATION => 'Communication',
            self::AUTRE => 'Autre',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::TRANSPORT => 'heroicon-o-truck',
            self::HEBERGEMENT => 'heroicon-o-home',
            self::RESTAURATION => 'heroicon-o-cake',
            self::CARBURANT => 'heroicon-o-fire',
            self::PEAGE => 'heroicon-o-banknotes',
            self::PARKING => 'heroicon-o-square-3-stack-3d',
            self::MATERIEL => 'heroicon-o-wrench-screwdriver',
            self::FORMATION => 'heroicon-o-academic-cap',
            self::COMMUNICATION => 'heroicon-o-phone',
            self::AUTRE => 'heroicon-o-ellipsis-horizontal',
        };
    }

    public function requiresKilometrage(): bool
    {
        return in_array($this, [self::TRANSPORT, self::CARBURANT]);
    }

    public function requiresJustificatif(): bool
    {
        return ! in_array($this, [self::TRANSPORT]);
    }
}

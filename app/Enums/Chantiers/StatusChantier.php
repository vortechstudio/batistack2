<?php

declare(strict_types=1);

namespace App\Enums\Chantiers;

use Filament\Support\Icons\Heroicon;

enum StatusChantier: string
{
    case Planifie = 'planifie';
    case Progress = 'progress';
    case Terminer = 'terminer';
    case Annuler = 'annuler';

    public static function array()
    {
        return [
            'planifie' => 'Planifié',
            'progress' => 'En cours',
            'terminer' => 'Terminer',
            'annuler' => 'Annuler',
        ];
    }

    public function label()
    {
        return match ($this) {
            self::Planifie => __('Planifier'),
            self::Progress => __('En cours'),
            self::Terminer => __('Terminer'),
            self::Annuler => __('Annuler'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planifie => 'gray',
            self::Progress => 'amber',
            self::Terminer => 'green',
            self::Annuler => 'red',
            default => 'gray',
        };
    }

    public function icon(): Heroicon
    {
        return match ($this) {
            self::Planifie => Heroicon::CalendarDateRange,
            self::Progress => Heroicon::Clock,
            self::Terminer => Heroicon::CheckCircle,
            self::Annuler => Heroicon::XMark,
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Enums\RH;

use Filament\Support\Icons\Heroicon;

enum StatusAbsence: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label()
    {
        return match ($this) {
            self::PENDING => 'En Attente',
            self::APPROVED => 'Approuvé',
            self::REJECTED => 'Refusé',
            self::CANCELLED => 'Annulé',
            self::IN_PROGRESS => 'En Cours',
            self::COMPLETED => 'Terminé',
            default => 'Inconnu',
        };
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'amber',
            self::APPROVED, self::COMPLETED => 'green',
            self::REJECTED => 'red',
            self::CANCELLED => 'purple',
            self::IN_PROGRESS => 'blue',
            default => 'gray',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::PENDING, self::IN_PROGRESS => Heroicon::Clock,
            self::APPROVED, self::COMPLETED => Heroicon::Check,
            self::REJECTED => Heroicon::XMark,
            self::CANCELLED => Heroicon::XCircle,
            default => Heroicon::QuestionMarkCircle,
        };
    }
}

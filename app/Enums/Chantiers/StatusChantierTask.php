<?php

declare(strict_types=1);

namespace App\Enums\Chantiers;

use Filament\Support\Icons\Heroicon;

enum StatusChantierTask: string
{
    case Todo = 'todo';
    case Progress = 'progress';
    case Finished = 'finished';
    case Blocked = 'blocked';

    public static function array()
    {
        return [
            'todo' => 'todo',
            'progress' => 'progress',
            'finished' => 'finished',
            'blocked' => 'blocked',
        ];
    }

    public function label()
    {
        return match ($this) {
            self::Todo => 'A Faire',
            self::Progress => 'En Cours',
            self::Finished => 'Terminer',
            self::Blocked => 'Bloquer',
        };
    }

    public function color()
    {
        return match ($this) {
            self::Todo => 'primary',
            self::Progress => 'warning',
            self::Finished => 'success',
            self::Blocked => 'danger',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::Todo => Heroicon::TableCells,
            self::Progress => Heroicon::Clock,
            self::Finished => Heroicon::CheckCircle,
            self::Blocked => Heroicon::XMark,
        };
    }
}

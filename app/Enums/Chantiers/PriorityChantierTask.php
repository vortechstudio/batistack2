<?php

declare(strict_types=1);

namespace App\Enums\Chantiers;

enum PriorityChantierTask: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public static function array()
    {
        return [
            'low' => 'low',
            'medium' => 'medium',
            'high' => 'high',
        ];
    }

    public function label()
    {
        return match ($this) {
            self::Low => 'Basse',
            self::Medium => 'Moyenne',
            self::High => 'Haute',
        };
    }

    public function color()
    {
        return match ($this) {
            self::Low => 'blue',
            self::Medium => 'amber',
            self::High => 'red',
        };
    }
}

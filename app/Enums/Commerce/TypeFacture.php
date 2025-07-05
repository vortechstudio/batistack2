<?php

namespace App\Enums\Commerce;

enum TypeFacture: string
{
    case ACOMPTE = 'acompte';
    case SITUATION = 'situation';
    case FINAL = 'final';

    public function label()
    {
        return match ($this) {
            self::ACOMPTE => "Acompte",
            self::SITUATION => "Situation",
            self::FINAL => "",
        };
    }
}

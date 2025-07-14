<?php

namespace App\Enums\RH;

enum StatusEmploye: string
{
    case ACTIF = 'actif';
    case INACTIF = 'inactif';
    case CONGE = 'conge';
    case ABSCENT = 'abscent';

    public function label()
    {
         return match($this) {
            self::ACTIF => "Actif",
            self::INACTIF => "Inactif",
            self::CONGE => "Congé",
            self::ABSCENT => "Absent",
         };
    }

    public function color()
    {
         return match($this) {
            self::ACTIF => "green",
            self::INACTIF => "gray",
            self::CONGE => "amber",
            self::ABSCENT => "red",
         };
    }
}

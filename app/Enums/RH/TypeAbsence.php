<?php

namespace App\Enums\RH;

enum TypeAbsence: string
{
    case MALADIE = "maladie";
    case PAYED = "payed";
    case RTT = "rtt";
    case INJUSTIFIED = "injustified";
    case OTHER = "other";

    public function label()
    {
        return match($this) {
            self::MALADIE => "Congés Maladie",
            self::PAYED => "Congés Payé",
            self::RTT => "RTT",
            self::INJUSTIFIED => "Absence Injustifiée",
            self::OTHER => "Autre",
        };
    }
}

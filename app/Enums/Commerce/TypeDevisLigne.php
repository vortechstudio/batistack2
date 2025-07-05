<?php

namespace App\Enums\Commerce;

enum TypeDevisLigne: string
{
    case PRODUCT = 'product';
    case SERVICE = 'service';
    case FABRICATION = 'fabrication';

    public function label()
    {
        return match ($this) {
            self::PRODUCT => 'Produit',
            self::SERVICE => 'Service',
            self::FABRICATION => 'Fabrication',
        };
    }
}

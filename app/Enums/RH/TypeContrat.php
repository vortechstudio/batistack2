<?php

namespace App\Enums\RH;

enum TypeContrat: string
{
    case CDI = 'cdi';
    case CDD = 'cdd';
    case INTERIM = 'interim';
    case APPRENTI = 'apprenti';
}

<?php

namespace App\Enums\RH;

enum TypeContrat: string
{
    case CDI = 'cdi';
    case CDD = 'cdd';
    case INTERIM = 'interim';
    case APPRENTI = 'apprenti';


    public static function array()
    {
         return collect(self::cases())->map(function($type) {
            return [
                'value' => $type->value,
                'label' => $type->name,
            ];
         });
    }

    public function label()
    {
        return self::array()->firstWhere('value', $this->value)['label'];
    }
}

<?php

namespace App\Enums\RH;

enum StatusContrat: string
{
    case DRAFT = 'draft';
    case CHECKED = 'checked';
    case ACTIF = 'actif';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';

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
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::CHECKED => 'Validé',
            self::ACTIF => 'Actif',
            self::SUSPENDED => 'Suspendu',
            self::TERMINATED => 'Terminé',
        };
    }

    public function color()
    {
         return match($this) {
            self::DRAFT => 'gray',
            self::CHECKED => 'blue',
            self::ACTIF => 'green',
            self::SUSPENDED => 'amber',
            self::TERMINATED => 'red',
        };
    }
}

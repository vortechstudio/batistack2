<?php

declare(strict_types=1);

namespace App\Enums\Commerce;

enum StatusDevis: string
{
    case DRAFT = 'draft';
    case SUBMIT = 'submit';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function label()
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::SUBMIT => 'Envoyé',
            self::ACCEPTED => 'Accepté',
            self::REJECTED => 'Rejeté',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color()
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::SUBMIT => 'blue',
            self::ACCEPTED => 'green',
            self::REJECTED, self::CANCELLED => 'red',
        };
    }
}

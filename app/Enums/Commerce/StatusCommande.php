<?php

namespace App\Enums\Commerce;

use Filament\Support\Icons\Heroicon;

enum StatusCommande: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case WAITING = 'waiting';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    public function label()
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::CONFIRMED => 'Confirmé',
            self::WAITING => 'En cours',
            self::DELIVERED => 'Livré',
            self::CANCELED => 'Annulé',
        };
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::CONFIRMED => 'indigo',
            self::WAITING => 'amber',
            self::DELIVERED => 'green',
            self::CANCELED => 'red',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::PENDING => Heroicon::Clock,
            self::CONFIRMED, self::DELIVERED => Heroicon::CheckCircle,
            self::WAITING => Heroicon::Truck,
            self::CANCELED => Heroicon::XCircle,
        };
    }
}

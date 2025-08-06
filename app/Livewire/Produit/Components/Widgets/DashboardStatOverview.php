<?php

declare(strict_types=1);

namespace App\Livewire\Produit\Components\Widgets;

use App\Models\Produit\Produit;
use App\Models\Produit\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class DashboardStatOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de produits', Produit::count()),
            Stat::make('Total des services', Service::count()),
            Stat::make('Produits disponibles à l\'achat', Produit::disponibleAchat()->count()),
            Stat::make('Produits disponibles à la vente', Produit::disponibleVente()->count()),
        ];
    }
}

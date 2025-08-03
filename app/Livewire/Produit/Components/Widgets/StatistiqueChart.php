<?php

declare(strict_types=1);

namespace App\Livewire\Produit\Components\Widgets;

use App\Models\Produit\Produit;
use Filament\Widgets\ChartWidget;

final class StatistiqueChart extends ChartWidget
{
    protected ?string $heading = 'Statistiques des Produits';

    protected function getData(): array
    {
        // Nombre de produits disponibles à l'achat uniquement
        $disponiblesAchat = Produit::where('achat', true)
            ->where('vente', false)
            ->count();

        // Nombre de produits disponibles à la vente uniquement
        $disponiblesVente = Produit::where('vente', true)
            ->where('achat', false)
            ->count();

        // Nombre de produits disponibles à l'achat ET à la vente
        $disponiblesAchatVente = Produit::where('achat', true)
            ->where('vente', true)
            ->count();

        // Nombre de produits non disponibles ni à l'achat ni à la vente
        $nonDisponibles = Produit::where('achat', false)
            ->where('vente', false)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Répartition des produits',
                    'data' => [
                        $disponiblesAchat,
                        $disponiblesVente,
                        $disponiblesAchatVente,
                        $nonDisponibles,
                    ],
                    'backgroundColor' => [
                        '#3B82F6', // Bleu pour achat uniquement
                        '#10B981', // Vert pour vente uniquement
                        '#8B5CF6', // Violet pour achat et vente
                        '#EF4444', // Rouge pour non disponibles
                    ],
                    'borderColor' => [
                        '#1D4ED8',
                        '#059669',
                        '#7C3AED',
                        '#DC2626',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                'Disponibles à l\'achat uniquement',
                'Disponibles à la vente uniquement',
                'Disponibles achat et vente',
                'Non disponibles',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) {
                            const label = context.label || "";
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return label + ": " + value + " (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}

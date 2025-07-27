<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Widgets;

use App\Enums\RH\TypeFrais;
use App\Models\RH\NoteFraisDetail;
use Filament\Widgets\ChartWidget;

final class StatsFrais extends ChartWidget
{
    protected ?string $heading = 'Stats Frais';

    protected function getData(): array
    {
        // Récupérer les totaux par type de frais
        $stats = NoteFraisDetail::query()
            ->selectRaw('type_frais, SUM(montant_ttc) as total')
            ->whereHas('noteFrais', function ($query) {
                // Optionnel : filtrer par statut si nécessaire
                // $query->where('statut', '!=', 'brouillon');
            })
            ->groupBy('type_frais')
            ->get();

        // Préparer les labels et données
        $labels = [];
        $data = [];
        $backgroundColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
            '#4BC0C0', '#FF6384',
        ];

        foreach ($stats as $stat) {
            $labels[] = $stat->type_frais->label();
            $data[] = (float) $stat->total;
        }

        // S'assurer que les tableaux sont indexés numériquement
        $labels = array_values($labels);
        $data = array_values($data);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Montant €',
                    'data' => $data,
                    'backgroundColor' => array_slice($backgroundColors, 0, count($data)),
                    'borderWidth' => 2,
                    'borderColor' => '#fff',
                ],
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
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + new Intl.NumberFormat("fr-FR", {
                                style: "currency",
                                currency: "EUR"
                            }).format(context.parsed);
                        }',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}

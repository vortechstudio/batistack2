<?php

namespace App\Livewire\Chantier\Widget;

use App\Models\Chantiers\Chantiers;
use Filament\Widgets\ChartWidget;

class ChartRentability extends ChartWidget
{
    protected ?string $heading = 'Chart Rentability';
    public Chantiers $chantier;

    protected function getData(): array
    {
        return [
            'labels' => ["Main D'oeuvre", "Achats", "Marges"],
            'datasets' => [
                'label' => "Montant HT",
                'data' => [2500, (float)$this->chantier->budget_estime, $this->chantier->marge_chantier],
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

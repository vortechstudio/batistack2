<?php

declare(strict_types=1);

namespace App\Livewire\Chantier\Widget;

use App\Models\Chantiers\Chantiers;
use Filament\Widgets\ChartWidget;

final class ChartRentability extends ChartWidget
{
    public Chantiers $chantier;

    protected ?string $heading = 'Chart Rentability';

    protected function getData(): array
    {
        return [
            'labels' => ["Main D'oeuvre", 'Achats', 'Marges'],
            'datasets' => [
                'label' => 'Montant HT',
                'data' => [2500, (float) $this->chantier->budget_estime, $this->chantier->marge_chantier],
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

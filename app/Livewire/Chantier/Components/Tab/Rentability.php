<?php

namespace App\Livewire\Chantier\Components\Tab;

use App\Models\Chantiers\Chantiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Rentability extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;
    public Chantiers $chantier;
    public array $chartRentability = [];

    public function mount(): void
    {
        $this->chartRentability = [
            'type' => 'doughnut',
            'data' => [
                'labels' => ["Main d'oeuvre", "Achats", "Marges"],
                'datasets' => [
                    [
                        'label' => "Montant Ht",
                        'data' => [2500, $this->chantier->budget_estime, $this->chantier->marge_chantier],
                        'backgroundColor' => [
                            'oklch(78.9% 0.154 211.53)',
                            'oklch(77.7% 0.152 181.912)',
                            'oklch(67.3% 0.182 276.935)',
                        ]
                    ]
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.chantier.components.tab.rentability');
    }
}

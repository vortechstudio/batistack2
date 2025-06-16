<?php

namespace App\Livewire\Tiers;

use App\Models\Tiers\Tiers;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    public int $countClient;
    public int $countFournisseur;
    public array $statTiers = [];

    public function mount(): void
    {
        $this->countClient = Tiers::where('nature', 'client')->get()->count();
        $this->countFournisseur = Tiers::where('nature', 'fournisseur')->get()->count();
        $this->statTiers = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Clients', 'Fournisseurs'],
                'datasets' => [
                    [
                        'data' => [$this->countClient, $this->countFournisseur],
                    ],
                ]
            ]
        ];
    }
    #[Title("Gestion des Tiers")]
    public function render()
    {
        return view('livewire.tiers.dashboard')
            ->layout('components.layouts.tiers');
    }
}

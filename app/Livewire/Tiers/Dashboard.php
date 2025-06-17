<?php

namespace App\Livewire\Tiers;

use App\Enums\Tiers\TiersNature;
use App\Models\Tiers\Tiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;
    public int $countClient;
    public int $countFournisseur;
    public array $statTiers = [];
    public $tiers;

    public function mount(): void
    {
        $this->countClient = Tiers::where('nature', 'client')->get()->count();
        $this->countFournisseur = Tiers::where('nature', 'fournisseur')->get()->count();
        $this->tiers = Tiers::orderByDesc('id')->limit(5)->get();
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

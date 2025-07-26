<?php

declare(strict_types=1);

namespace App\Livewire\Tiers;

use App\Models\Tiers\Tiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Dashboard extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public int $countClient;

    public int $countFournisseur;

    public array $statTiers = [];

    public $tiers;

    public function mount(): void
    {
        $this->countClient = Tiers::where('nature', 'client')->count();
        $this->countFournisseur = Tiers::where('nature', 'fournisseur')->count();
        $this->tiers = Tiers::orderByDesc('id')->limit(5)->get();
        $this->statTiers = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Clients', 'Fournisseurs'],
                'datasets' => [
                    [
                        'data' => [$this->countClient, $this->countFournisseur],
                    ],
                ],
            ],
        ];
    }

    #[Title('Gestion des Tiers')]
    #[Layout('components.layouts.tiers')]
    public function render()
    {
        return view('livewire.tiers.dashboard');
    }
}

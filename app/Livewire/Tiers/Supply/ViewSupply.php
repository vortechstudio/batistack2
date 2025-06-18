<?php

namespace App\Livewire\Tiers\Supply;

use App\Models\Tiers\Tiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class ViewSupply extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;
    public Tiers $tiers;

    public function mount(int $id): void
    {
        $this->tiers = Tiers::with('addresses', 'contacts', 'fournisseur', 'client', 'logs')->find($id);
    }



    public function render()
    {
        //dd($this->tiers->fournisseur()->with('comptaGen', 'comptaFournisseur')->first());
        return view('livewire.tiers.supply.view-supply')
            ->layout('components.layouts.tiers');
    }
}

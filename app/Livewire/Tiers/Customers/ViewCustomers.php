<?php

namespace App\Livewire\Tiers\Customers;

use App\Models\Tiers\Tiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class ViewCustomers extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public Tiers $tiers;

    public function mount(int $id): void
    {
        $this->tiers = Tiers::with('addresses', 'contacts', 'fournisseur', 'client', 'logs')->find($id);
    }

    public function render()
    {
        return view('livewire.tiers.customers.view-customers')
            ->layout('components.layouts.tiers');
    }
}

<?php

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersFournisseur;
use Livewire\Component;

class TabSupply extends Component
{
    public Tiers $tiers;
    public TiersFournisseur $supply;

    public function mount(): void
    {
        $this->supply = $this->tiers->fournisseur()->with('comptaGen', 'comptaFournisseur')->first();
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.tab-supply');
    }
}

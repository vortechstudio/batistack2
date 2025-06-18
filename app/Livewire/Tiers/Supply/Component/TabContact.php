<?php

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use Livewire\Component;

class TabContact extends Component
{
    public Tiers $tiers;

    public function mount(Tiers $tiers): void
    {
        $this->tiers = $tiers;
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.tab-contact');
    }
}

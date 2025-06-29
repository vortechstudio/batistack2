<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use Livewire\Component;

final class TabBank extends Component
{
    public Tiers $tiers;

    public function mount(Tiers $tiers): void
    {
        $this->tiers = $tiers;
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.tab-bank');
    }
}

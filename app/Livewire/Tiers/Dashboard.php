<?php

namespace App\Livewire\Tiers;

use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{

    #[Title("Gestion des Tiers")]
    public function render()
    {
        return view('livewire.tiers.dashboard')
            ->layout('components.layouts.tiers');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply;

use Livewire\Attributes\Title;
use Livewire\Component;

final class CreateSupply extends Component
{
    #[Title("Création d'un fournisseur")]
    public function render()
    {
        return view('livewire.tiers.supply.create-supply')
            ->layout('components.layouts.tiers');
    }
}

<?php

namespace App\Livewire\Tiers\Supply;

use Livewire\Attributes\Title;
use Livewire\Component;

class EditSupply extends Component
{
    public int $id;

    public function mount(int $id)
    {
        $this->id = $id;
    }

    #[Title("Edition d'un fournisseur")]
    public function render()
    {
        return view('livewire.tiers.supply.edit-supply')
            ->layout('components.layouts.tiers');
    }
}

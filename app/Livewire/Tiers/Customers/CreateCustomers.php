<?php

namespace App\Livewire\Tiers\Customers;

use Livewire\Attributes\Title;
use Livewire\Component;

class CreateCustomers extends Component
{
    #[Title("Création d'un nouveau client")]
    public function render()
    {
        return view('livewire.tiers.customers.create-customers')
            ->layout('components.layouts.tiers');
    }
}

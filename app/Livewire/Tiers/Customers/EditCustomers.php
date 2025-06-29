<?php

namespace App\Livewire\Tiers\Customers;

use Livewire\Attributes\Title;
use Livewire\Component;

class EditCustomers extends Component
{
    public int $id;

    public function mount($id): void
    {
        $this->id = $id;
    }

    #[Title("Edition d'un client")]
    public function render()
    {
        return view('livewire.tiers.customers.edit-customers')
            ->layout('components.layouts.tiers');
    }
}

<?php

namespace App\Livewire\Produit;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Title("Tableau de bord")]
    #[Layout('components.layouts.produit')]
    public function render()
    {
        return view('livewire.produit.dashboard');
    }
}

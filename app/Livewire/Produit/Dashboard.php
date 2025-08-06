<?php

declare(strict_types=1);

namespace App\Livewire\Produit;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Dashboard extends Component
{
    #[Title('Tableau de bord')]
    #[Layout('components.layouts.produit')]
    public function render()
    {
        return view('livewire.produit.dashboard');
    }
}

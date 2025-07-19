<?php

namespace App\Livewire\Portail\Salarie;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Title('Tableau de Bord')]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        return view('livewire.portail.salarie.dashboard');
    }
}

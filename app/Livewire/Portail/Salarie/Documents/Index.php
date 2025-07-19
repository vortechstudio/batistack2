<?php

namespace App\Livewire\Portail\Salarie\Documents;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{

    #[Title("Mes Documents")]
    #[Layout("components.layouts.portail.salarie")]
    public function render()
    {
        return view('livewire.portail.salarie.documents.index');
    }
}

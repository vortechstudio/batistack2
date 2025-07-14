<?php

namespace App\Livewire\Chantier\Components\Tab;

use App\Models\Chantiers\Chantiers;
use Livewire\Component;

class Gallery extends Component
{
    public Chantiers $chantier;
    
    public function render()
    {
        return view('livewire.chantier.components.tab.gallery');
    }
}

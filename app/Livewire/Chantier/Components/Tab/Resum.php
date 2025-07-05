<?php

declare(strict_types=1);

namespace App\Livewire\Chantier\Components\Tab;

use App\Models\Chantiers\Chantiers;
use Livewire\Component;

final class Resum extends Component
{
    public Chantiers $chantier;

    public function render()
    {
        return view('livewire.chantier.components.tab.resum');
    }
}

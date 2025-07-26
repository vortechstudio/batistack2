<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Salarie;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Index extends Component
{
    #[Title('Espace Salariés')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.salarie.index');
    }
}

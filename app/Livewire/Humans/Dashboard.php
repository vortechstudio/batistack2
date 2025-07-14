<?php

namespace App\Livewire\Humans;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Title('Espace RH')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.dashboard');
    }
}

<?php

namespace App\Livewire\Humans;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.dashboard');
    }
}

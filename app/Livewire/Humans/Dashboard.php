<?php

declare(strict_types=1);

namespace App\Livewire\Humans;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Dashboard extends Component
{
    #[Title('Espace RH')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.dashboard');
    }
}

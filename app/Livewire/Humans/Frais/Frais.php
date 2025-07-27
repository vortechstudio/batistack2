<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Frais;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Frais extends Component
{
    #[Title('Note de Frais')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.frais.frais');
    }
}

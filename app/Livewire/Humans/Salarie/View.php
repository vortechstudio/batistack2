<?php

namespace App\Livewire\Humans\Salarie;

use App\Models\RH\Employe;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class View extends Component
{
    public Employe $salarie;

    public function mount(int $id)
    {
        $this->salarie = Employe::find($id);
    }

    #[Title('Fiche d\'un Salarie')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.salarie.view');
    }
}

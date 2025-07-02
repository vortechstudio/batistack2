<?php

namespace App\Livewire\Chantier;

use App\Models\Chantiers\Chantiers;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

class View extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Chantiers $chantier;

    public function mount(int $id): void
    {
        $this->chantier = Chantiers::find($id);
    }


    #[Title('Chantier')]
    public function render()
    {
        return view('livewire.chantier.view')
            ->layout('components.layouts.chantiers');
    }
}

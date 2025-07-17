<?php

namespace App\Livewire\Humans\Salarie;

use App\Models\RH\Employe;
use Filament\Schemas\Components\View as ComponentsView;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class View extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Employe $salarie;
    public ?array $contractValidatedData = [];

    protected function getForms()
    {
        return [
            'contractValidatedForm',
        ];
    }

    public function mount(int $id)
    {
        $this->salarie = Employe::find($id);
    }

    public function contractValidatedForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            ])
            ->statePath('contractValidatedData');
    }

    public function validateContract()
    {

    }

    #[Title('Fiche d\'un Salarie')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.salarie.view');
    }
}

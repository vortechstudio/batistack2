<?php

namespace App\Livewire\Settings;

use App\Models\Core\PlanComptable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreatePlan extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('code')->label('Code Comptable')->required(),
                        TextInput::make('account')->label('Libellée du compte')->required(),
                    ]),
                Grid::make(4)
                    ->schema([
                        Select::make('principal')->label('Groupe')->options(PlanComptable::all()->pluck('principal', 'principal')),
                        Select::make('type')->label('Type de Compte')->options(PlanComptable::all()->pluck('type', 'type')),
                        TextInput::make('initial')->label('Balance Initial')->suffix('€'),
                        Toggle::make('lettrage')->label('Lettrage'),
                    ])
            ])
            ->statePath('data');
    }

    public function createPlan(): void
    {
        PlanComptable::create($this->form->getState());
        toastr()->success("Le compte à été créer avec succès");
        $this->redirect(route('settings.pcg'));
    }

    #[Title("Création d'un compte")]
    public function render()
    {
        return view('livewire.settings.create-plan');
    }
}

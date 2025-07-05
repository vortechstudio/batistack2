<?php

namespace App\Livewire\Settings;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

class SettingsApp extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $devisData = [];
    public ?array $commandesData = [];

    public function mount(): void
    {
        $this->editDevisForm->fill(settings()->all()->toArray());
    }

    public function getForms(): array
    {
        return [
            'editDevisForm',
        ];
    }

    public function editDevisForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('action')->default('devis'),

                Toggle::make('num_devis_model')
                ->label("Modèles de numérotation des devis")
                ->hint("Renvoie le numéro sous la forme DEyymm-nnnn où yy est l'année, mm le mois et nnnn un compteur séquentiel sans rupture et sans remise à 0. Ex: DE2501-0001"),

                TextInput::make('devis_validity')
                ->label('Délai de validité des devis (jours)')
                ->default(30),

                RichEditor::make('devis_mention')
                ->label('Mention complémentaire sur les devis'),


            ])
            ->statePath('devisData');
    }

    public function updateSettings()
    {
        if(!empty($this->editDevisForm->getState())) {
            settings()->set('num_devis_model', $this->editDevisForm->getState()['num_devis_model']);
            settings()->set('devis_validity', $this->editDevisForm->getState()['devis_validity']);
            settings()->set('devis_mention', $this->editDevisForm->getState()['devis_mention']);
        }
    }

    #[Title("Paramètre de l'application")]
    public function render()
    {
        return view('livewire.settings.settings-app')
            ->layout('components.layouts.settings');
    }
}

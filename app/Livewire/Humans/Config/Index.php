<?php

namespace App\Livewire\Humans\Config;

use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Log;

class Index extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(settings()->all()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expert Comptable (Paie)')
                    ->schema([
                        TextInput::make('expert_comptable_paie_email')
                            ->label('Email')
                            ->email(),

                        TextInput::make('expert_comptable_paie_phone')
                            ->label('Téléphone')
                            ->tel(),

                        TextInput::make('expert_comptable_paie_name')
                            ->label('Nom')
                            ->required(),

                    ])
                    ->collapsed(),
            ]);
    }

    public function update()
    {
        try {
            !empty($this->form->getState()['expert_comptable_paie_email']) ? settings()->set('expert_comptable_paie_email', $this->form->getState()['expert_comptable_paie_email']) : settings()->set('expert_comptable_paie_email', '');
            !empty($this->form->getState()['expert_comptable_paie_phone']) ? settings()->set('expert_comptable_paie_phone', $this->form->getState()['expert_comptable_paie_phone']) : settings()->set('expert_comptable_paie_phone', '');
            !empty($this->form->getState()['expert_comptable_paie_name']) ? settings()->set('expert_comptable_paie_name', $this->form->getState()['expert_comptable_paie_name']) : settings()->set('expert_comptable_paie_name', '');
        }catch(Exception $ex) {
            report($ex);
            Log::channel('github')->emergency($ex);
            Notification::make()
                ->danger()
                ->title("Configuration RH")
                ->body("Erreur lors de la mise à jour de la configuration de l'application")
                ->send();
        }
    }

    #[Title('Configuration')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.config.index');
    }
}

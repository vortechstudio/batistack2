<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

final class SettingsApp extends Component implements HasForms
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
                Section::make('Configuration des devis client')
                    ->schema([
                        TextInput::make('devis_validity')
                            ->label('Durée de validité des devis'),

                        TextArea::make('devis_mention')
                            ->label('Mention complémentaire'),
                    ])
                    ->collapsed(),

                Section::make('Tableau de Bord')
                    ->schema([
                        Section::make('Message du tableau de bord')
                            ->description("Ajoute un message d'alerte sur le tableau de bord général de l'application")
                            ->schema([
                                Toggle::make('dashboard_message_active')
                                    ->label("Activer le message d'alerte"),

                                TextInput::make('dashboard_message_title')
                                    ->label('Titre du message'),

                                Textarea::make('dashboard_message_body')
                                    ->label('Texte du message'),

                                Grid::make()
                                    ->schema([
                                        DatePicker::make('dashboard_message_date_start')
                                            ->label('Date de début'),

                                        DatePicker::make('dashboard_message_date_end')
                                            ->label('Date de fin'),
                                    ])
                            ])
                            ->collapsed()
                    ])
                    ->collapsed(),

            ])
            ->statePath('data');
    }

    public function update()
    {
        try {
            !empty($this->form->getState()['devis_validity']) ? settings()->set('devis_validity', $this->form->getState()['devis_validity']) : settings()->set('devis_validity', '');
            !empty($this->form->getState()['devis_mention']) ? settings()->set('devis_mention', $this->form->getState()['devis_mention']) : settings()->set('devis_mention', '');
            !empty($this->form->getState()['dashboard_message_active']) ? settings()->set('dashboard_message_active', $this->form->getState()['dashboard_message_active']) : settings()->set('dashboard_message_active', '');
            !empty($this->form->getState()['dashboard_message_title']) ? settings()->set('dashboard_message_title', $this->form->getState()['dashboard_message_title']) : settings()->set('dashboard_message_title', '');
            !empty($this->form->getState()['dashboard_message_body']) ? settings()->set('dashboard_message_body', $this->form->getState()['dashboard_message_body']) : settings()->set('dashboard_message_body', '');
            !empty($this->form->getState()['dashboard_message_date_start']) ? settings()->set('dashboard_message_date_start', $this->form->getState()['dashboard_message_date_start']) : settings()->set('dashboard_message_date_start', '');
            !empty($this->form->getState()['dashboard_message_date_end']) ? settings()->set('dashboard_message_date_end', $this->form->getState()['dashboard_message_date_end']) : settings()->set('dashboard_message_date_end', '');

            Notification::make()
                ->success()
                ->title("Configuration de l'application")
                ->body("La mise à jour de la configuration de l'application à été effectué avec succès")
                ->send();
        }catch (\Exception $exception){
            report($exception);
            Notification::make()
                ->danger()
                ->title("Configuration de l'application")
                ->body("Erreur lors de la mise à jour de la configuration de l'application")
                ->send();
        }
    }

    #[Title("Paramètre de l'application")]
    public function render()
    {
        return view('livewire.settings.settings-app')
            ->layout('components.layouts.settings');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Core\Country;
use App\Services\Bridge;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Log;

final class Company extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public \App\Models\Core\Company $company;

    public ?array $data = [];

    public function mount(): void
    {
        $this->company = \App\Models\Core\Company::first();
        $this->form->fill($this->company->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identité de la société')
                    ->description("Paramètres de l'identité de la société")
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom de la société'),

                        Textarea::make('address')
                            ->label('Adresse Postal'),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('code_postal')
                                    ->label('Code Postal'),

                                TextInput::make('ville')
                                    ->label('Ville'),

                                Select::make('pays')
                                    ->label('Pays')
                                    ->options(Country::query()->pluck('name', 'name')),
                            ]),

                        Grid::make(4)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Téléphone')
                                    ->mask('99 99 99 99 99'),

                                TextInput::make('fax')
                                    ->label('Fax')
                                    ->mask('99 99 99 99 99'),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email(),

                                TextInput::make('web')
                                    ->label('Site Web'),
                            ]),
                    ]),

                Section::make('Fiscalité')
                    ->description('Information fiscale de la société')
                    ->aside()
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('siret')
                                    ->label('Siret')
                                    ->mask('999 999 999 99999'),

                                TextInput::make('num_tva')
                                    ->label('TVA'),

                                TextInput::make('ape')
                                    ->label('APE/NAF'),

                                TextInput::make('capital')
                                    ->label('Capital de la société'),
                            ]),

                        TextInput::make('rcs')
                            ->label('RCS de la société'),
                    ]),

                Section::make('Logo de la société')
                    ->aside()
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo de la société')
                            ->disk('public')
                            ->directory('company')
                            ->visibility('public'),

                        FileUpload::make('logo_wide_path')
                            ->label('Logo long de la société')
                            ->disk('public')
                            ->directory('company')
                            ->visibility('public'),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateCompany()
    {
        try {
            $this->company->update([
                'name' => $this->form->getState()['name'],
                'address' => $this->form->getState()['address'],
                'code_postal' => $this->form->getState()['code_postal'],
                'ville' => $this->form->getState()['ville'],
                'pays' => $this->form->getState()['pays'],
                'phone' => $this->form->getState()['phone'],
                'fax' => $this->form->getState()['fax'],
                'email' => $this->form->getState()['email'],
                'web' => $this->form->getState()['web'],
                'siret' => $this->form->getState()['siret'],
                'num_tva' => $this->form->getState()['num_tva'],
                'ape' => $this->form->getState()['ape'],
                'capital' => $this->form->getState()['capital'],
                'rcs' => $this->form->getState()['rcs'],
            ]);

            $this->company->refresh();

            if (empty($this->company->bridge_client_id)) {
                $clt = app(Bridge::class)->post('/aggregation/users', [
                    'external_user_id' => 'CPT'.rand(10000, 999999),
                ]);
                $this->company->update([
                    'bridge_client_id' => $clt['uuid'],
                ]);
                app(Bridge::class)->getAccessToken();
            }

            Notification::make()
                ->success()
                ->title('Paramètres mis à jour')
                ->body('Les paramètres de la société ont été mis à jour avec succès.')
                ->send();
        } catch (Exception $ex) {
            Log::emergency($ex->getMessage());
        }
    }

    #[Title('Paramètre de la Société')]
    #[Layout('components.layouts.settings')]
    public function render()
    {
        return view('livewire.settings.company');
    }
}

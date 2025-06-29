<?php

namespace App\Livewire\Settings;

use App\Models\Core\City;
use App\Models\Core\Country;
use App\Rules\VAT;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Company extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    use Toast;
    use WithFileUploads;

    public \App\Models\Core\Company $company;

    public ?array $data = [];

    public $logo;

    public $logo_wide;

    public function mount(): void
    {
        $this->company = \App\Models\Core\Company::first();
        $this->form->fill($this->company->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model($this->company)
            ->statePath('data')
            ->components([
                TextInput::make('name')
                    ->label('Raison Sociale')
                    ->columns(1),

                TextInput::make('address')
                    ->label('Adresse')
                    ->columns(1),

                Grid::make()
                    ->columns(3)
                    ->schema([
                        TextInput::make('code_postal')->label('Code Postal')->live(true),

                        Select::make('ville')
                            ->label('Ville')
                            ->live()
                            ->options(fn (Get $get) => City::whereLike('postal_code', $get('code_postal'))->pluck('city', 'city')),

                        Select::make('pays')
                            ->label('Pays')
                            ->options(Country::all()->pluck('name', 'name')),
                    ]),

                Section::make('Coordonnées')
                    ->schema([
                        Grid::make()
                            ->columns(4)
                            ->schema([
                                TextInput::make('phone')->label('Téléphone')->tel()->mask('99 99 99 99 99'),
                                TextInput::make('fax')->label('Fax')->tel()->mask('99 99 99 99 99'),
                                TextInput::make('email')->label('Email')->email(),
                                TextInput::make('web')->label('Site Web')->url(),
                            ]),
                    ]),

                Section::make('Informations Fiscales')
                    ->schema([
                        Grid::make()
                            ->columns(4)
                            ->schema([
                                TextInput::make('siret')->label('Siret')->mask('999 999 999 99999'),
                                TextInput::make('num_tva')->label('Numéro de TVA')->mask('aa99999999999')->rules([new VAT]),
                                TextInput::make('ape')->label('Code NAF/APE')->mask('9999a'),
                                TextInput::make('capital')->label('Capital'),
                            ]),
                        TextInput::make('rcs')->label('RCS'),
                    ]),
            ]);
    }

    public function create(): void
    {
        $this->company->update($this->form->getState());
        toastr()->success('Les informations de la société ont été mise à jours');
    }

    public function uploadLogo(): void
    {
        try {
            $this->logo->storeAs(
                path: 'societe',
                name: 'logo.png',
            );

            $this->logo_wide->storeAs(
                path: 'societe',
                name: 'logo_wide.png',
            );

            toastr()->success('Le logo est enregistré');
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
        }
    }

    #[Title('Paramètre de la Société')]
    public function render()
    {
        return view('livewire.settings.company')
            ->layout('components.layouts.settings');
    }
}

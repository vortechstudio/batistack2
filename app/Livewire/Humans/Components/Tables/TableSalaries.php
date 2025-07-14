<?php

namespace App\Livewire\Humans\Components\Tables;

use App\Enums\RH\TypeContrat;
use App\Helpers\RH\CreateEmploye;
use App\Models\Core\City;
use App\Models\Core\Country;
use App\Models\RH\Employe;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TableSalaries extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des salariés')
            ->selectable()
            ->query(Employe::query())
            ->headerActions([
                CreateAction::make('create')
                    ->icon(Heroicon::PlusCircle)
                    ->color('info')
                    ->iconButton()
                    ->tooltip('Nouveau salarié')
                    ->schema([
                        Wizard::make([
                            Step::make('Identité')
                                ->icon(Heroicon::Identification)
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Select::make('civility')
                                                ->label('Civilite')
                                                ->options([
                                                    'Monsieur' => 'Monsieur',
                                                    'Madame' => 'Madame',
                                                    'Mademoiselle' => 'Mademoiselle',
                                                ])
                                                ->required(),

                                            TextInput::make('nom')
                                                ->label('Nom')
                                                ->required(),

                                            TextInput::make('prenom')
                                                ->label('Prénom')
                                                ->required(),
                                    ]),

                                    Textarea::make('address')
                                        ->label('Adresse Postal')
                                        ->required(),

                                    Grid::make()
                                        ->schema([
                                            TextInput::make('code_postal')
                                                ->label('Code Postal')
                                                ->required(),

                                            TextInput::make('ville')
                                                ->label('Ville')
                                                ->required(),
                                    ]),

                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('telephone')
                                                ->label('Téléphone')
                                                ->required(),

                                            TextInput::make('portable')
                                                ->label('Mobile'),

                                            TextInput::make('email')
                                                ->label('Email')
                                                ->required(),
                                        ]),
                                ]),

                            Step::make('Informations Supplémentaire')
                                ->icon(Heroicon::InformationCircle)
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            DatePicker::make('date_naissance')
                                                ->label('Date de naissance'),

                                            TextInput::make('lieu_naissance')
                                                ->label('Lieu de Naissance'),

                                            Select::make('pays_naissance')
                                                ->label('Pays de naissance')
                                                ->options(Country::all()->pluck('name', 'name')->toArray()),

                                            TextInput::make('num_cni')
                                                ->label('Numéro de CNI'),

                                            TextInput::make('num_secu')
                                                ->label('Numéro de Sécurité Social'),

                                            TextInput::make('num_passport')
                                                ->label('Numéro de passport'),
                                        ]),

                                    Grid::make()
                                        ->schema([
                                                TextInput::make('num_permis_btp')
                                                    ->label('Numéro de permis BTP'),

                                                TextInput::make('exp_permis_btp')
                                                    ->label('Date d\'expiration du permis BTP'),
                                        ])
                                ]),

                            Step::make('Information de contrat')
                                ->icon(Heroicon::Document)
                                ->schema([
                                    Select::make('type')
                                        ->label('Type de Contrat')
                                        ->options(TypeContrat::array()->pluck('label', 'value')->toArray())
                                        ->required(),

                                    TextInput::make('poste')
                                        ->label('Poste'),

                                    Grid::make()
                                        ->schema([
                                            DateTimePicker::make('date_debut')
                                                ->label("Date d'embauche")
                                                ->required(),

                                            DateTimePicker::make('date_fin')
                                                ->label('Date de fin de contrat'),

                                            TextInput::make('salaire_horaire')
                                                ->label('Salaire Horaire')
                                                ->suffix('€')
                                                ->hint('Salaire Horaire brut de l\'heure'),

                                            TextInput::make('heure_travail')
                                                ->label('Heure de travail')
                                                ->suffix('h/Semaine'),
                                        ])
                                ])
                        ])
                    ])
                    ->using(function (array $data, CreateEmploye $employe) {
                        $employe->create($data);
                    }),
            ])
            ->columns([
                TextColumn::make('full_name')
                    ->label('Identifiant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('telephone')
                    ->label('Coordonnées'),

                TextColumn::make('poste')
                    ->label('Poste'),

                TextColumn::make('status')
                    ->label('Etat')
                    ->sortable(),
            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-salaries');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Tables;

use App\Enums\RH\TypeContrat;
use App\Helpers\RH\CreateEmploye;
use App\Helpers\RH\UpdateEmploye;
use App\Models\Core\Country;
use App\Models\RH\Employe;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Storage;

final class TableSalaries extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des salariés')
            ->selectable()
            ->query(Employe::query()->with('info', 'contrat'))
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
                                                ->options(Country::pluck('name', 'name'))
                                                ->searchable()
                                                ->preload(),

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
                                        ]),
                                ]),

                            Step::make('Information de contrat')
                                ->icon(Heroicon::Document)
                                ->schema([
                                    Select::make('type')
                                        ->label('Type de Contrat')
                                        ->options(TypeContrat::array()->pluck('label', 'value'))
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
                                        ]),
                                ]),
                        ]),
                    ])
                    ->using(function (array $data, CreateEmploye $employe) {
                        $employe->create($data);
                    }),
            ])
            ->columns([
                TextColumn::make('full_name')
                    ->label('Identifiant')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString(
                            $record->full_name.'<br>'.$record->email
                        );
                    }),

                TextColumn::make('telephone')
                    ->label('Coordonnées')
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString(
                            'Tel: '.$record->telephone.'<br>Port: '.$record->portable
                        );
                    }),

                TextColumn::make('poste')
                    ->label('Poste'),

                TextColumn::make('status')
                    ->label('Etat')
                    ->sortable()
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString("<span class='inline-flex items-center rounded-md bg-{$record->status->color()}-100 px-2 py-1 text-xs font-medium text-{$record->status->color()}-600 ring-1 ring-{$record->status->color()}-500/10 ring-inset'>{$record->status->label()}</span>");
                    }),
            ])
            ->recordActions([
                ViewAction::make('view')
                    ->tooltip('Fiche du salarié')
                    ->iconButton()
                    ->color('info')
                    ->url(fn (Model $record) => route('humans.salaries.view', $record->id)),

                EditAction::make('edit')
                    ->mutateRecordDataUsing(function (array $data, Model $record) {
                        $data['date_naissance'] = $record->info->date_naissance;
                        $data['lieu_naissance'] = $record->info->lieu_naissance;
                        $data['pays_naissance'] = $record->info->pays_naissance;
                        $data['num_cni'] = $record->info->num_cni;
                        $data['num_secu'] = $record->info->num_secu;
                        $data['num_permis_btp'] = $record->info->num_permis_btp;
                        $data['exp_permis_btp'] = $record->info->exp_permis_btp;
                        $data['type'] = $record->contrat->type;
                        $data['date_debut'] = $record->contrat->date_debut;
                        $data['date_fin'] = $record->contrat->date_fin;
                        $data['salaire_horaire'] = $record->contrat->salaire_horaire;
                        $data['heure_travail'] = $record->contrat->heure_travail;

                        return $data;
                    })
                    ->tooltip('Editer le salarié')
                    ->iconButton()
                    ->color('warning')
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

                                    Textarea::make('adresse')
                                        ->label('Adresse Postal')
                                        ->default(fn (Model $record) => $record->adresse)
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
                                                ->options(Country::pluck('name', 'name')),

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
                                        ]),
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
                                        ]),
                                ]),
                        ]),
                    ])
                    ->using(function (array $data, UpdateEmploye $employe, ?Model $record) {
                        $employe->update($data, $record);
                    }),

                DeleteAction::make('delete')
                    ->tooltip('Supprimer le salarié')
                    ->requiresConfirmation()
                    ->iconButton()
                    ->action(function (Model $record) {
                        Storage::disk('public')->deleteDirectory('rh/salarie/'.$record->id);
                        $record->delete();
                    }),

                Action::make('impersonate')
                    ->tooltip('Ce connecte en tant que ce salarié')
                    ->icon(Heroicon::Key)
                    ->iconButton()
                    ->url(fn (?Model $record) => route('impersonate', $record->user->id)),

            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-salaries');
    }
}

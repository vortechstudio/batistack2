<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Tables;

use App\Enums\RH\ModePaiementFrais;
use App\Enums\RH\StatusNoteFrais;
use App\Enums\RH\TypeFrais;
use App\Models\Chantiers\Chantiers;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class TableFraisDetails extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public NoteFrais $frais;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Détails de la note de frais')
            ->headerActions($this->frais->statut === StatusNoteFrais::BROUILLON ? [
                CreateAction::make('create')
                    ->label('Ajouter une ligne de frais')
                    ->icon(Heroicon::Plus)
                    ->schema([
                        Wizard::make([
                            Step::make('Details')
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            DatePicker::make('date_frais')
                                                ->label('Date')
                                                ->minDate(fn () => $this->frais->date_debut)
                                                ->maxDate(fn () => $this->frais->date_fin)
                                                ->required(),

                                            Select::make('type_frais')
                                                ->label('Type de frais')
                                                ->options(TypeFrais::getSelectOptions())
                                                ->required(),
                                        ]),

                                    TextInput::make('libelle')
                                        ->label('Designation')
                                        ->required(),

                                    Grid::make()
                                        ->schema([
                                            TextInput::make('fournisseur')
                                                ->label('Fournisseur ou Lieu')
                                                ->required(),

                                            TextInput::make('numero_facture')
                                                ->label('Numéro de facture'),
                                        ]),

                                    Textarea::make('commentaire')
                                        ->label('Commentaire'),
                                ]),

                            Step::make('Montants')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('montant_ttc')
                                                ->label('Montant TTC')
                                                ->required(),

                                            TextInput::make('taux_tva')
                                                ->label('Taux de TVA')
                                                ->required(),

                                            Select::make('mode_paiement')
                                                ->label('Mode de paiement')
                                                ->options(ModePaiementFrais::getSelectOptions())
                                                ->required(),
                                        ]),
                                ]),

                            Step::make('Chantier')
                                ->schema([
                                    Select::make('chantier_id')
                                        ->label('Chantier')
                                        ->searchable()
                                        ->options(Chantiers::pluck('libelle', 'id')),

                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('lieu_depart')
                                                ->label('Lieu de départ'),

                                            TextInput::make('lieu_arrivee')
                                                ->label('Lieu d\'arrivée'),

                                            TextInput::make('kilometrage')
                                                ->label('Kilométrage'),
                                        ]),
                                ]),

                            Step::make('Justificatif')
                                ->schema([
                                    FileUpload::make('justificatif_path')
                                        ->label('Justificatif')
                                        ->disk('frais')
                                        ->directory(now()->year.'/'.now()->month)
                                        ->visibility('public')
                                        ->required()
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                                            $strNoteFrais = Str::slug($this->frais->numero, separator: '_');
                                            $now = now()->format('Y_m_d_H_i_s');

                                            return $strNoteFrais.'_'.$now.'_'.$file->hashName();
                                        }),
                                ]),
                        ]),
                    ])
                    ->using(function (array $data) {
                        $data['note_frais_id'] = $this->frais->id;
                        NoteFraisDetail::create($data);
                        $this->frais->refresh();
                    }),
            ] : [])
            ->query(NoteFraisDetail::query()->where('note_frais_id', $this->frais->id))
            ->columns([
                TextColumn::make('date_frais')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('chantier_id')
                    ->label('Chantier')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return Chantiers::find($state)->libelle;
                    }),

                TextColumn::make('type_frais')
                    ->label('Type')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->formatStateUsing(fn (?Model $record) => $record->type_frais->label()),

                TextColumn::make('libelle')
                    ->label('Designation')
                    ->description(fn ($record) => $record->commentaire),

                TextColumn::make('taux_tva')
                    ->label('TVA'),

                TextColumn::make('montant_ht')
                    ->label('Montant HT')
                    ->money('EUR', locale: 'fr'),

                TextColumn::make('montant_ttc')
                    ->label('Montant TTC')
                    ->money('EUR', locale: 'fr'),

                TextColumn::make('mode_paiement')
                    ->label('Mode de paiement')
                    ->searchable(isIndividual: true)
                    ->formatStateUsing(fn (?Model $record) => $record->mode_paiement->label()),
            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-frais-details');
    }
}

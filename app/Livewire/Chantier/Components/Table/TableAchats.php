<?php

namespace App\Livewire\Chantier\Components\Table;

use App\Enums\Chantiers\TypeDepenseChantier;
use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use Carbon\Carbon;
use Exception;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TableAchats extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(ChantierDepense::query()->where('chantiers_id', $this->chantier->id))
            ->headerActions([
                CreateAction::make('new_achat')
                    ->label('Nouvelle depense')
                    ->modalHeading('Nouvelle depense')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Select::make('tiers_id')
                                    ->label('Fournisseur')
                                    ->searchable()
                                    ->options(Tiers::where('nature', 'fournisseur')->pluck('name', 'id')->toArray())
                                    ->required(),

                                Select::make('type_depense')
                                    ->label('Type de depense')
                                    ->options(TypeDepenseChantier::array())
                                    ->required(),

                                DatePicker::make('date_depense')
                                    ->label('Date de depense')
                                    ->required(),

                                TextInput::make('montant')
                                    ->label('Montant')
                                    ->required(),
                            ]),

                            Textarea::make('description')
                                ->required()
                                ->label('Description'),

                            TextInput::make('invoice_ref')
                                ->label('Numero de facture'),

                            FileUpload::make('justificatifs')
                                ->label('Justificatifs')
                                ->multiple()
                                ->required()
                                ->visibility('public')
                                ->panelLayout('grid')
                                ->directory('chantiers/'.$this->chantier->id.'/justificatifs')
                                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, Get $get) {
                                    return "depense_".$get('tiers_id')."_".Carbon::createFromTimestamp(strtotime($get('date_depense')))->format('d_m_Y')."_".$get('type_depense').".".$file->getClientOriginalExtension();
                                })
                                ->disk('public'),
                    ])
                    ->using(function (array $data) {
                        try {
                            $this->chantier->depenses()->create([
                                'description' => $data['description'],
                                'montant' => $data['montant'],
                                'date_depense' => $data['date_depense'],
                                'type_depense' => $data['type_depense'],
                                'invoice_ref' => $data['invoice_ref'],
                                'tiers_id' => $data['tiers_id'],
                                'chantiers_id' => $this->chantier->id,
                            ]);
                        } catch (Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    }),
            ])
            ->columns([
                TextColumn::make('description')
                    ->label('Description'),

                TextColumn::make('date_depense')
                    ->sortable()
                    ->date('d/m/Y')
                    ->label('Date de depense'),

                TextColumn::make('type_depense')
                    ->sortable()
                    ->label('Type de depense'),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->money('EUR')
                    ->summarize(Sum::make()->money('EUR')->label('Total de depense')),
            ])
            ->filters([
                SelectFilter::make('type_depense')
                    ->options([
                        "materiel" => "Materiel",
                        "main_oeuvre" => "Main d'Oeuvre",
                        "sous_traitance" => "Sous traitance",
                        "transport" => "Transport",
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-achats');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Chantiers;

use App\Enums\Chantiers\StatusChantier;
use App\Enums\Tiers\TiersNature;
use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Devis;
use App\Models\Commerce\Facture;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

final class Dashboard extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public ?array $data = [];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des chantiers')
            ->headerActions([
                CreateAction::make()
                    ->label('Ajouter un chantier')
                    ->color('primary')
                    ->schema([
                        Select::make('tiers_id')
                            ->label('Client')
                            ->searchable()
                            ->required()
                            ->options(Tiers::where('nature', TiersNature::Client)->pluck('name', 'id')->toArray()),

                        TextInput::make('libelle')
                            ->label('Nom du chantier')
                            ->required(),

                        Textarea::make('description')
                            ->label('Description du chantier'),

                        Grid::make()
                            ->schema([
                                DatePicker::make('date_debut')
                                    ->label('Date de début Prévu')
                                    ->format('Y-m-d'),

                                DatePicker::make('date_fin')
                                    ->label('Date de fin prévu')
                                    ->format('Y-m-d'),
                            ]),

                        Grid::make()
                            ->schema([
                                Select::make('responsable_id')
                                    ->label('Responsable')
                                    ->searchable()
                                    ->options(User::all()->pluck('name', 'id')->toArray()),

                                Select::make('intervenants')
                                    ->label('Intervenants')
                                    ->searchable()
                                    ->multiple()
                                    ->options(User::all()->pluck('name', 'id')->toArray()),
                            ]),

                        Checkbox::make('other_address')
                            ->label('Le chantier est à une adresse différente de celle du client'),
                    ])
                    ->using(function (array $data) {
                        try {
                            $chantier = Chantiers::create([
                                'libelle' => $data['libelle'],
                                'description' => $data['description'],
                                'date_debut' => $data['date_debut'],
                                'date_fin_prevu' => $data['date_fin'],
                                'responsable_id' => $data['responsable_id'],
                                'tiers_id' => $data['tiers_id'],
                                'budget_estime' => 0,
                                'budget_reel' => 0,
                            ]);

                            foreach ($data['intervenants'] as $intervenant) {
                                $chantier->users()->attach($intervenant);
                            }

                            if (! $data['other_address']) {
                                $chantier->addresses()->create([
                                    'address' => $chantier->tiers()->first()->addresses()->first()->address,
                                    'code_postal' => $chantier->tiers()->first()->addresses()->first()->code_postal,
                                    'ville' => $chantier->tiers()->first()->addresses()->first()->ville,
                                    'pays' => $chantier->tiers()->first()->addresses()->first()->pays,
                                    'chantiers_id' => $chantier->id,
                                ]);
                            }

                            return $chantier;
                        } catch(Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    }),

                Action::make('print')
                    ->label('Imprimer')
                    ->color('secondary')
                    ->action(function (Table $table) {
                        try {
                            $pdfBuilder = Pdf::view('pdf.chantier.listing_chantier', ['chantiers' => $table->getRecords()->all()])
                                ->format(Format::A4)
                                ->landscape()
                                ->margins(1, 2, 2, 2, Unit::Centimeter)
                                ->name('listing_chantier.pdf');

                            return response()->streamDownload(function () use ($pdfBuilder) {
                                echo base64_decode($pdfBuilder->download()->base64());
                            }, 'listing_chantier.pdf');
                        } catch(Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    }),
            ])
            ->query(Chantiers::query())
            ->columns([
                TextColumn::make('tiers_id')
                    ->label('Client / Chantier')
                    ->sortable()
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString("
                            <div class='flex flex-col'>
                                <span class='font-bold'>{$record->tiers->name}</span>
                                <span class='italic text-gray-400'>$record->libelle</span>
                            </div>");
                    }),

                TextColumn::make('avancement')
                    ->label('Avancement')
                    ->sortable()
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString("
<div class='flex flex-row items-center'>
    <progress value='{$record->getAvancements()['percent']}' max='100' class='progress progress-info me-5'></progress>
    <span class='inline-flex items-center rounded-md bg-{$record->getAvancements()['color']}-100 px-2 py-1 text-xs font-medium text-{$record->getAvancements()['color']}-600 ring-1 ring-{$record->getAvancements()['color']}-500/10 ring-inset'>{$record->getAvancements()['percent']} %</span>
</div>
                        ");
                    }),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->sortable()
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString("<span class='inline-flex items-center rounded-md bg-{$record->status->color()}-100 px-2 py-1 text-xs font-medium text-{$record->status->color()}-600 ring-1 ring-{$record->status->color()}-500/10 ring-inset'>{$record->status->label()}</span>");
                    }),

                TextColumn::make('date_debut')
                    ->label('Date de début')
                    ->sortable()
                    ->date('d/m/Y'),

                TextColumn::make('devis_count')
                    ->label('Devis')
                    ->formatStateUsing(fn (?Model $record) => Devis::where('chantiers_id', $record->id)->count()),

                TextColumn::make('facture_count')
                    ->label('Factures')
                    ->formatStateUsing(fn (?Model $record) => Facture::where('chantiers_id', $record->id)->count()),

            ])
            ->recordActions([
                ActionGroup::make([
                    ActionGroup::make([
                        ViewAction::make('view')
                            ->label('Vue du chantier')
                            ->url(fn (Chantiers $chantiers) => route('chantiers.view', $chantiers->id)),

                        EditAction::make('edit')
                            ->label('Modifier chantier'),

                        DeleteAction::make('delete')
                            ->label('Supprimer chantier'),
                    ])->dropdown(false),

                    ActionGroup::make([
                        Action::make('createDevis')
                            ->icon(Heroicon::Document)
                            ->label('Nouveau Devis'),

                        Action::make('createFacture')
                            ->icon(Heroicon::Document)
                            ->label('Nouvelle Facture'),

                        Action::make('createPv')
                            ->icon(Heroicon::Document)
                            ->label('PV de réception'),
                    ])->dropdown(false),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusChantier::array()),

                SelectFilter::make('tiers_id')
                    ->options(Tiers::all()->pluck('name', 'id')),

                QueryBuilder::make()
                    ->constraints([
                        QueryBuilder\Constraints\DateConstraint::make('date_debut'),
                    ]),
            ]);
    }

    #[Title('Tableau de Bord - Chantiers')]
    #[Layout('components.layouts.chantiers')]
    public function render()
    {
        return view('livewire.chantiers.dashboard');
    }
}

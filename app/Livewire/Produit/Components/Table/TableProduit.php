<?php

namespace App\Livewire\Produit\Components\Table;

use App\Actions\Produit\NewProduct;
use App\Enums\Produits\TauxTVA;
use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Filament\Exports\Produit\ProduitExporter;
use App\Models\Core\PlanComptable;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use Exception;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;
use Storage;

class TableProduit extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Produit::query()->with(['category', 'entrepot', 'tarifClient', 'stockPrincipal']))
            ->heading('Liste des produits')
            ->recordClasses(function (?Model $record) {
                $stock = $record->stockPrincipal;
                if (!$stock) {
                    return 'bg-gray-200';
                }
                return match($stock->getStatutStock()) {
                    'rupture' => 'bg-red-200',
                    'critique' => 'bg-amber-200',
                    'faible' => 'bg-blue-200',
                    'normal' => 'bg-green-200',
                    default => 'bg-gray-200'
                };
            })
            ->recordUrl(fn (?Model $record) => route('produit.produit.show', $record->id))
            ->columns([
                TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable(isIndividual: true),

                TextColumn::make('name')
                    ->label('Libellé')
                    ->searchable(isIndividual: true),

                TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->searchable(isIndividual: true),

                TextColumn::make('tarifClient.prix_unitaire')
                    ->label('Prix unitaire'),

                TextColumn::make('stockPrincipal.quantite')
                    ->label('Quantité en stock'),

                TextColumn::make('stock_status')
                    ->label('')
                    ->getStateUsing(function (Produit $record): string {
                        $stock = $record->stockPrincipal;
                        if (!$stock) {
                            return 'Aucun stock';
                        }
                        return match($stock->getStatutStock()) {
                            'rupture' => 'Rupture',
                            'critique' => 'Critique',
                            'faible' => 'Faible',
                            'normal' => 'Normal',
                            default => 'Inconnu'
                        };
                    })
                    ->color(function (Produit $record): string {
                        $stock = $record->stockPrincipal;
                        if (!$stock) {
                            return 'gray';
                        }
                        return match($stock->getStatutStock()) {
                            'rupture' => 'danger',
                            'critique' => 'warning',
                            'faible' => 'info',
                            'normal' => 'success',
                            default => 'gray'
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Catégorie')
                    ->options(Category::query()->pluck('name', 'id')),
            ])
            ->headerActions([
                CreateAction::make('create')
                    ->label('Créer un produit')
                    ->icon(Heroicon::PlusCircle)
                    ->schema([
                        Wizard::make([
                            Step::make('Produit')
                                ->schema([
                                    Grid::make(4)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Libellé')
                                                ->required(),

                                            TextInput::make('serial_number')
                                                ->label('Numéro de série'),

                                            Checkbox::make('achat')
                                                ->label("Disponible à l'achat"),

                                            Checkbox::make('vente')
                                                ->label("Disponible à la vente"),
                                        ]),

                                    Grid::make(3)
                                        ->schema([
                                            Select::make('category_id')
                                                ->label('Catégorie')
                                                ->options(Category::query()->pluck('name', 'id'))
                                                ->required(),

                                            Select::make('entrepot_id')
                                                ->label('Entrepôt')
                                                ->options(Entrepot::query()->where('status', 1)->pluck('name', 'id'))
                                                ->required(),

                                            Select::make('code_comptable_vente')
                                                ->label('Code comptable de vente')
                                                ->options(
                                                    PlanComptable::query()
                                                        ->where('type', 'Revenus')
                                                        ->get()
                                                        ->mapWithKeys(function ($planComptable) {
                                                            return [$planComptable->id => $planComptable->code . ' - ' . $planComptable->account];
                                                        })
                                                        ->toArray()
                                                )
                                                ->searchable(),
                                        ]),

                                    RichEditor::make('description')
                                        ->label('Description'),

                                    Section::make('Poids')
                                        ->schema([
                                            Grid::make()
                                                ->schema([
                                                    TextInput::make('poids_value')
                                                        ->label('Poids'),

                                                    Select::make('poids_unite')
                                                        ->label('Unité')
                                                        ->options(UnitePoids::getSelectOptions()),
                                                ]),
                                        ]),

                                    Section::make('Dimension')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('longueur')
                                                        ->label('longueur'),

                                                    TextInput::make('largeur')
                                                        ->label('Largeur'),

                                                    TextInput::make('hauteur')
                                                        ->label('Hauteur'),

                                                    Select::make('llh_unite')
                                                        ->label('Unité')
                                                        ->options(UniteMesure::getSelectOptions()),
                                                ]),
                                        ]),
                            ]),

                            Step::make('Tarification')
                                ->schema([
                                    Repeater::make('tarifClient')
                                        ->label('Tarifs Clients')
                                        ->schema([
                                            TextInput::make('prix_unitaire')
                                                ->label('Prix unitaire')
                                                ->required(),

                                            Select::make('taux_tva')
                                                ->label('Taux TVA')
                                                ->options(TauxTVA::getSelectOptions())
                                                ->required(),
                                        ])
                                        ->columns(2),

                                    Repeater::make('tarifFournisseur')
                                            ->label('Tarifs Fournisseur')
                                            ->schema([
                                                TextInput::make('ref_fournisseur')
                                                    ->label('Référence')
                                                    ->required(),

                                                TextInput::make('qte_minimal')
                                                    ->label('Quantité minimal')
                                                    ->default(1),

                                                TextInput::make('prix_unitaire')
                                                    ->label('Prix unitaire'),

                                                TextInput::make('delai_livraison')
                                                    ->label('Delai de livraison')
                                                    ->default(1)
                                                    ->suffix('Jours'),

                                                TextInput::make('barrecode')
                                                    ->label('Code barre'),
                                            ])
                                            ->columns(5)
                                ]),

                            Step::make('Stock')
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            TextInput::make('limit_stock')
                                                ->label('Seuil de stock')
                                                ->default(10),

                                            TextInput::make('optimal_stock')
                                                ->label('Stock optimal'),
                                        ]),

                                    Repeater::make('stockInitial')
                                        ->label('Stock Initial')
                                        ->schema([
                                            Select::make('entrepot_id')
                                                ->label('Entrepôt')
                                                ->options(Entrepot::query()->where('status', 1)->pluck('name', 'id'))
                                                ->required(),

                                            TextInput::make('quantite')
                                                ->label('Quantité')
                                                ->default(0),
                                        ])
                                        ->columns(2),
                                ]),
                        ])
                    ])
                    ->using(function (array $data) {
                        try {
                            app(NewProduct::class)->handle($data);

                            Notification::make()
                                ->title('Succès')
                                ->body('Produit créé avec succès')
                                ->success()
                                ->send();
                        } catch(Exception $ex) {
                            Log::emergency($ex->getMessage());
                            Notification::make()
                                ->title('Erreur')
                                ->body($ex->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('allDelete')
                        ->label('Supprimer')
                        ->icon(Heroicon::Trash)
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    ExportAction::make('export')
                        ->label('Exporter')
                        ->tooltip('Export en xls')
                        ->icon(Heroicon::TableCells)
                        ->requiresConfirmation()
                        ->formats([
                            ExportFormat::Xlsx
                        ])
                        ->exporter(ProduitExporter::class),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.produit.components.table.table-produit');
    }
}

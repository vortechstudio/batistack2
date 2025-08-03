<?php

namespace App\Livewire\Produit\Components\Table;

use App\Filament\Exports\Produit\ProduitExporter;
use App\Models\Produit\Produit;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

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

            ])
            ->headerActions([

            ])
            ->recordActions([

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

                    BulkAction::make('print')
                        ->label('Imprimer')
                        ->icon(Heroicon::Printer),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.produit.components.table.table-produit');
    }
}

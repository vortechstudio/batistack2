<?php

namespace App\Livewire\Produit\Components\Widgets;

use App\Models\Produit\Produit;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class DashboardTableProduit extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des produits')
            ->query(fn (): Builder => Produit::query()->with([
                'tarifClient',
                'stockPrincipal' => function ($query) {
                    $query->with('produit'); // Charger la relation produit pour éviter les requêtes N+1 dans getStatutStock()
                }
            ])->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('reference')
                    ->label(''),

                TextColumn::make('name')
                    ->label(''),

                TextColumn::make('updated_at')
                    ->label('')
                    ->date('d/m/Y'),

                TextColumn::make('tarifClient.prix_unitaire')
                    ->label('')
                    ->money('EUR', 0, 'fr_FR'),

                TextColumn::make('stock_status')
                    ->label('')
                    ->getStateUsing(function (Produit $record): string {
                        $stock = $record->stockPrincipal;
                        if (!$stock) {
                            return 'Aucun stock';
                        }

                        // Utiliser les valeurs du produit directement pour éviter les requêtes N+1
                        if ($stock->quantite <= 0) {
                            return 'Rupture';
                        }

                        if ($record->limit_stock && $stock->quantite <= $record->limit_stock) {
                            return 'Critique';
                        }

                        if ($record->optimal_stock && $stock->quantite <= $record->optimal_stock) {
                            return 'Faible';
                        }

                        return 'Normal';
                    })
                    ->color(function (Produit $record): string {
                        $stock = $record->stockPrincipal;
                        if (!$stock) {
                            return 'gray';
                        }

                        // Utiliser les valeurs du produit directement pour éviter les requêtes N+1
                        if ($stock->quantite <= 0) {
                            return 'danger';
                        }

                        if ($record->limit_stock && $stock->quantite <= $record->limit_stock) {
                            return 'warning';
                        }

                        if ($record->optimal_stock && $stock->quantite <= $record->optimal_stock) {
                            return 'info';
                        }

                        return 'success';
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}

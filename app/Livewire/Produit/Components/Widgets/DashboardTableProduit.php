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
            ->query(fn (): Builder => Produit::query()->with(['tarifClient', 'stockPrincipal'])->limit(5))
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

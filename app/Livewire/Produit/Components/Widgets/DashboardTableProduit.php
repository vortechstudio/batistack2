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
            ->query(fn (): Builder => Produit::query())
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

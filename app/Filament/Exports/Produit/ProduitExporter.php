<?php

declare(strict_types=1);

namespace App\Filament\Exports\Produit;

use App\Models\Produit\Produit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class ProduitExporter extends Exporter
{
    protected static ?string $model = Produit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('reference'),
            ExportColumn::make('name'),
            ExportColumn::make('serial_number'),
            ExportColumn::make('limit_stock'),
            ExportColumn::make('optimal_stock'),
            ExportColumn::make('poids_value'),
            ExportColumn::make('poids_unite'),
            ExportColumn::make('longueur'),
            ExportColumn::make('largeur'),
            ExportColumn::make('hauteur'),
            ExportColumn::make('llh_unite'),
            ExportColumn::make('category.name'),
            ExportColumn::make('entrepot.name'),
            ExportColumn::make('stockPrincipal.quantity'),
            ExportColumn::make('tarifClient.prix_unitaire')
                ->formatStateUsing(fn (string $state) => Number::currency($state, 'EUR', 'FR')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your produit export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}

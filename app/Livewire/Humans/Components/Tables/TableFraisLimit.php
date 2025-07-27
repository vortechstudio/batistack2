<?php

namespace App\Livewire\Humans\Components\Tables;

use Livewire\Component;
use App\Models\RH\NoteFrais;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class TableFraisLimit extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(NoteFrais::limit(3))
            ->heading('Les 3 dernières notes de frais')
            ->columns([
                TextColumn::make('numero')
                    ->label(''),

                TextColumn::make('employe_id')
                    ->label(''),

                TextColumn::make('montant_total')
                    ->money('EUR', 0, 'fr'),

                TextColumn::make('updated_at')
                    ->date('d/m/Y'),

            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-frais-limit');
    }
}

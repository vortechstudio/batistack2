<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Tables;

use App\Models\RH\Employe;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

final class TableBank extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public Employe $employe;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Comptes Bancaires')
            ->headerActions([])
            ->query($this->employe->bank->query())
            ->columns([
                TextColumn::make('bank_name')
                    ->label('Banque'),

                TextColumn::make('iban')
                    ->label('Iban'),

                TextColumn::make('bic')
                    ->label('BIC'),
            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-bank');
    }
}

<?php

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TableBank extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public Tiers $tiers;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Comptes Bancaires')
            ->query($this->tiers->banks()->with('bank')->getQuery())
            ->columns([
                TextColumn::make('bank.name')
                    ->label('Banque'),

                TextColumn::make('iban')
                    ->label('IBAN'),

                TextColumn::make('bic')
                    ->label('BIC/SWIFT'),

                TextColumn::make('external_id')
                    ->label('ID Externe'),

                CheckboxColumn::make('default')
                ->label('Default'),
            ]);
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.table-bank');
    }
}

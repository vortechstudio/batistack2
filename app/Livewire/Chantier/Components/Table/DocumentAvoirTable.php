<?php

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Avoir;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class DocumentAvoirTable extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;
    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(Avoir::query()->where('chantiers_id', $this->chantier->id))
            ->recordUrl(fn (Model $record) => route('chantiers.view', $record->id))
            ->columns([
                TextColumn::make('num_avoir')
                    ->label('Réference')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date_avoir')
                    ->label('Date du devis')
                    ->sortable()
                    ->date(),

                TextColumn::make('amount_ht')
                    ->label('Montant HT')
                    ->money('EUR'),

                TextColumn::make('amount_ttc')
                    ->label('Montant TTC')
                    ->money('EUR'),

            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.document-avoir-table');
    }
}

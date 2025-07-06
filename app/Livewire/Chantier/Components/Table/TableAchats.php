<?php

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\Chantiers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;

class TableAchats extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(ChantierDepense::query()->where('chantiers_id', $this->chantier->id))
            ->columns([
                TextColumn::make('description')
                    ->label('Description'),

                TextColumn::make('date_depense')
                    ->sortable()
                    ->date('d/m/Y')
                    ->label('Date de depense'),

                TextColumn::make('type_depense')
                    ->sortable()
                    ->label('Type de depense'),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->money('EUR')
                    ->summarize(Sum::make()->money('EUR')->label('Total de depense')),
            ])
            ->filters([
                SelectFilter::make('type_depense')
                    ->options([
                        "materiel" => "Materiel",
                        "main_oeuvre" => "Main d'Oeuvre",
                        "sous_traitance" => "Sous traitance",
                        "transport" => "Transport",
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-achats');
    }
}

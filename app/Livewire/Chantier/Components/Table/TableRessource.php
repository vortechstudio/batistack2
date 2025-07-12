<?php

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\ChantierRessources;
use App\Models\Chantiers\Chantiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class TableRessource extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(ChantierRessources::where('chantier_id', $this->chantier->id))
            ->columns([
                TextColumn::make('employe.user.name')
                    ->label('Identité')
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('')
                    ->formatStateUsing(fn (Model $record) => $record->duration)
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-ressource');
    }
}

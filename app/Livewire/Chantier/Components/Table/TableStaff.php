<?php

declare(strict_types=1);

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\ChantierRessources;
use App\Models\Chantiers\Chantiers;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

final class TableStaff extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(ChantierRessources::where('chantiers_id', $this->chantier->id))
            ->columns([
                Stack::make([
                    ImageColumn::make('employe.user.avatar')
                        ->label('')
                        ->circular()
                        ->width('5%'),

                    TextColumn::make('employe.user.name')
                        ->label('')
                        ->formatStateUsing(fn (?Model $record) => $record->employe->user->name)
                        ->description(fn (?Model $record) => $record->employe->user->email)
                        ->alignStart(),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-staff');
    }
}

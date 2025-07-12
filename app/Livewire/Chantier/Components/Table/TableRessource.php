<?php

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\ChantierRessources;
use App\Models\Chantiers\Chantiers;
use Creativeorange\Gravatar\Facades\Gravatar;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Highlight\Mode;
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
            ->query(ChantierRessources::where('chantiers_id', $this->chantier->id))
            ->columns([



                ImageColumn::make('employe.user.avatar')
                    ->label('')
                    ->circular()
                    ->width('5%'),

                TextColumn::make('employe.user.name')
                    ->label('')
                    ->formatStateUsing(fn (?Model $record) => $record->employe->user->name)
                    ->description(fn (?Model $record) => $record->employe->user->email)
                    ->alignStart(),

                TextColumn::make('duration')
                    ->label('')
                    ->formatStateUsing(fn (?Model $record) => $record->duration." H"),

                TextColumn::make('amount_fee')
                    ->label('')
                    ->money('EUR', locale: 'fr'),

            ])
            ->recordActions([
                DeleteAction::make('delete')
                    ->icon(Heroicon::Trash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (ChantierRessources $ressource) {
                        $ressource->delete();
                        Toastr::addSuccess("Ressource Supprimer");
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-ressource');
    }
}

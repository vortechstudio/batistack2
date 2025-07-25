<?php

declare(strict_types=1);

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\ChantierRessources;
use App\Models\Chantiers\Chantiers;
use App\Models\RH\Employe;
use Exception;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

final class TableRessource extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(ChantierRessources::where('chantiers_id', $this->chantier->id))
            ->headerActions([
                CreateAction::make('create')
                    ->schema([
                        Select::make('employe_id')
                            ->label('Ressources')
                            ->options(Employe::all()->pluck('full_name', 'id'))
                            ->required(),

                        TextInput::make('role')
                            ->label('Role')
                            ->required(),

                        DateTimePicker::make('date_affectation')
                            ->label('Date affectation')
                            ->required(),

                        DateTimePicker::make('date_fin')
                            ->label('Date fin')
                            ->required(),
                    ])
                    ->using(function (array $data) {
                        dd($data);
                    }),
            ])
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
                    ->formatStateUsing(fn (?Model $record) => $record->duration.' H'),

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
                        try {
                            $ressource->delete();
                        } catch (Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-ressource');
    }
}

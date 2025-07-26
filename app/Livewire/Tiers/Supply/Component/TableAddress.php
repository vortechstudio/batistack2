<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Core\City;
use App\Models\Core\Country;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersAddress;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

final class TableAddress extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public Tiers $tiers;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des adresses du tiers')
            ->headerActions([
                CreateAction::make()
                    ->label('Ajouter une adresse')
                    ->icon('heroicon-o-plus')
                    ->schema([
                        Hidden::make('tiers_id')
                            ->default($this->tiers->id),

                        TextInput::make('address')
                            ->label('Adresse du tier')
                            ->required(),

                        Grid::make()
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 3,
                                'xl' => 3,
                            ])
                            ->schema([
                                TextInput::make('code_postal')
                                    ->label('Code Postal')
                                    ->live(onBlur: true)
                                    ->required(),

                                Select::make('ville')
                                    ->label('Ville')
                                    ->live()
                                    ->options(fn (Get $get) => City::whereLike('postal_code', $get('code_postal'))->pluck('city', 'city')),

                                Select::make('pays')
                                    ->label('Pays')
                                    ->options(Country::pluck('name', 'name')),
                            ]),
                    ]),
            ])
            ->query($this->tiers->addresses()->getQuery())
            ->columns([
                Stack::make([
                    TextColumn::make('address')
                        ->label('Adresse'),

                    TextColumn::make('code_postal')
                        ->label('Code Postal'),

                    TextColumn::make('ville')
                        ->label('Ville'),

                    TextColumn::make('pays')
                        ->label('Pays'),
                ]),
            ])
            ->recordActions([
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (TiersAddress $record) => $record->delete()),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 6,
            ]);
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.table-address');
    }
}

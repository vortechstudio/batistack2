<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Core\Bank;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersBank;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Intervention\Validation\Rules\Bic;
use Intervention\Validation\Rules\Iban;
use Livewire\Component;

final class TableBank extends Component implements HasActions, HasForms, HasTable
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
            ->heading('Comptes Bancaires')
            ->query($this->tiers->banks()->with('bank')->getQuery())
            ->headerActions([
                CreateAction::make()
                    ->schema([
                        Select::make('bank_id')
                            ->label('Banque')
                            ->options(fn () => Bank::pluck('name', 'id'))
                            ->searchable(),

                        Grid::make()
                            ->schema([
                                TextInput::make('iban')
                                    ->label('IBAN')
                                    ->rules([new Iban])
                                    ->required(),

                                TextInput::make('bic')
                                    ->label('BIC/SWIFT')
                                    ->rules([new Bic])
                                    ->required(),
                            ]),

                        Hidden::make('tiers_id')
                            ->default($this->tiers->id),
                    ]),
            ])
            ->recordActions([
                EditAction::make('edit')
                    ->iconButton()
                    ->icon('heroicon-o-pencil')
                    ->schema([
                        Select::make('bank_id')
                            ->label('Banque')
                            ->options(fn () => Bank::pluck('name', 'id'))
                            ->searchable(),

                        Grid::make()
                            ->schema([
                                TextInput::make('iban')
                                    ->label('IBAN')
                                    ->rules([new Iban])
                                    ->required(),

                                TextInput::make('bic')
                                    ->label('BIC/SWIFT')
                                    ->rules([new Bic])
                                    ->required(),
                            ]),

                        Hidden::make('tiers_id')
                            ->default($this->tiers->id),
                    ]),

                DeleteAction::make('delete')
                    ->icon('heroicon-o-trash')
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (TiersBank $record): string => 'Supprimer '.$record->bank->name)
                    ->action(fn (TiersBank $record) => $record->delete()),
            ])
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

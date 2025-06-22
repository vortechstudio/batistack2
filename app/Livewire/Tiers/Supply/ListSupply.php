<?php

namespace App\Livewire\Tiers\Supply;

use App\Enums\Tiers\TiersNature;
use App\Enums\Tiers\TiersType;
use App\Helpers\Helpers;
use App\Models\Core\City;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersFournisseur;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Title;
use Livewire\Component;

class ListSupply extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Fournisseurs (' . TiersFournisseur::count() . ')')
            ->headerActions([
                Action::make('create')
                    ->label('Ajouter un fournisseur')
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Général')
                                ->completedIcon(Heroicon::CheckCircle)
                                ->description("Information usuel du tiers")
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Raison Social'),

                                    Grid::make()
                                        ->columns(2)
                                        ->schema([
                                            Select::make('type')
                                                ->label('Type de Tiers')
                                                ->options(TiersType::array()),

                                            Select::make('nature')
                                                ->label('Nature du tiers')
                                                ->options(TiersNature::array()),

                                            TextInput::make('code_tiers')
                                                ->label('Code Tiers')
                                                ->default(Helpers::generateCodeTiers('f')),

                                            TextInput::make('siren')
                                                ->label('Siren')
                                                ->mask('999 999 999')
                                                ->live(),

                                            Toggle::make('tva')
                                                ->label('TVA')
                                                ->live(),

                                            TextInput::make('num_tva')
                                                ->label('Numero TVA')
                                                ->mask('aa99 999 999 999')
                                                ->hidden(fn(Get $get): bool => !$get('tva')),
                                        ])

                                ]),

                            Wizard\Step::make('Adresse')
                                ->completedIcon(Heroicon::CheckCircle)
                                ->description("Adresse Postal du tiers")
                                ->schema([
                                    Textarea::make('address')
                                        ->label('Adresse Postal'),

                                    Grid::make()
                                        ->columns(3)
                                        ->schema([
                                            TextInput::make('code_postal')
                                                ->label('Code Postal')
                                                ->live(),

                                            Select::make('ville')
                                                ->label('Ville')
                                                ->searchable()
                                                ->options(fn(Get $get) => $get('code_postal') ? City::where('postal_code', $get('code_postal'))->pluck('cities.city', 'cities.city')->toArray() : City::get()->pluck('city', 'city')->toArray()),

                                            Select::make('pays')
                                                ->label('Pays')
                                                ->searchable(),
                                        ])
                                ])
                        ])
                    ]),
            ])
            ->query(Tiers::where('nature', 'fournisseur')->newQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Raison Sociale')
                    ->sortable()
                    ->searchable(isIndividual: true),

                TextColumn::make('code_tiers')
                    ->label('Code Tiers')
                    ->sortable()
                    ->searchable(isIndividual: true),

                TextColumn::make('type')
                    ->label('Type du tiers')
                    ->sortable()
                    ->searchable(isIndividual: true),

                TextColumn::make('contacts.0.tel')
                    ->label('Téléphone')
                    ->searchable(isIndividual: true),

                TextColumn::make('nature')
                    ->label('Nature du tiers')
                    ->sortable(),
            ])
            ->emptyStateHeading('Aucun fournisseur')
            ->toolbarActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn(Collection $records) => $records->each->delete()),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->url(fn(Tiers $record) => route('tiers.supply.view', $record->id))
                    ->icon('heroicon-s-eye'),
            ]);
    }

    #[Title('Liste des fournisseurs')]
    public function render()
    {
        return view('livewire.tiers.supply.list-supply')
            ->layout('components.layouts.tiers');
    }
}

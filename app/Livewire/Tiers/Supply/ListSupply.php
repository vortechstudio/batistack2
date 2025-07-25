<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply;

use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersFournisseur;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class ListSupply extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Fournisseurs ('.TiersFournisseur::count().')')
            ->headerActions([
                Action::make('create')
                    ->label('Ajouter un fournisseur')
                    ->url(route('tiers.supply.create')),
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
                    ->action(fn (Collection $records) => $records->each->delete()),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->url(fn (Tiers $record) => route('tiers.supply.view', $record->id))
                    ->icon('heroicon-s-eye'),
            ]);
    }

    #[Title('Liste des fournisseurs')]
    #[Layout('components.layouts.tiers')]
    public function render()
    {
        return view('livewire.tiers.supply.list-supply');
    }
}

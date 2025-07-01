<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Customers;

use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersClient;
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
use Livewire\Attributes\Title;
use Livewire\Component;

final class ListCustomers extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Clients ('.TiersClient::count().')')
            ->headerActions([
                Action::make('create')
                    ->label('Ajouter un client')
                    ->url(route('tiers.customers.create')),
            ])
            ->query(Tiers::where('nature', 'client')->newQuery())
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
                    ->url(fn (Tiers $record) => route('tiers.customers.view', $record->id))
                    ->icon('heroicon-s-eye'),
            ]);
    }

    #[Title('Liste des clients')]
    public function render()
    {
        return view('livewire.tiers.customers.list-customers')
            ->layout('components.layouts.tiers');
    }
}

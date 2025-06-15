<?php

namespace App\Livewire\Settings;

use App\Models\Core\PlanComptable;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Livewire\Attributes\Title;
use Livewire\Component;

class Plan extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(PlanComptable::all()->toQuery())
            ->columns([
                TextColumn::make('code')->label('Code Comptable')->searchable(isIndividual: true),
                TextColumn::make('account')->label('libelle Comptable')->searchable(isIndividual: true),
                TextColumn::make('type')->label('Type de Compte'),
                TextColumn::make('lettrage')
                    ->label('Lettrage')
                    ->badge()
                    ->color(fn(string $state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn(string $state) => $state ? 'Oui' : 'Non'),
                TextColumn::make('initial')
                    ->label('Balance Inital')->money('EUR'),
            ])
            ->filters([
                TernaryFilter::make('lettrage'),
                SelectFilter::make('principal')->label('Groupe')->options(PlanComptable::pluck('principal', 'principal')->toArray()),
                SelectFilter::make('type')->label('Type de Compte')->options(PlanComptable::pluck('type', 'type')->toArray()),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('deleteAction')
                    ->label('Supprimer')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn(PlanComptable $planComptable) => $planComptable->delete())
            ]);
    }

    public function createPlanAction(): Action
    {
        return CreateAction::make('createPlanModal')
            ->label('Créer un compte')
            ->url(route('settings.pcg.create'));
    }

    #[Title('Plan Comptable')]
    public function render()
    {
        return view('livewire.settings.plan')
            ->layout('components.layouts.settings');
    }
}

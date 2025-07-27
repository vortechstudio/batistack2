<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Core\ConditionReglement;
use App\Models\Core\ModeReglement;
use App\Models\Core\PlanComptable;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersFournisseur;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Livewire\Component;

final class TabSupply extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public Tiers $tiers;

    public ?TiersFournisseur $supply = null;

    public ?array $data = [];

    public function mount(): void
    {
        /** @var TiersFournisseur|null $supply */
        $supply = $this->tiers->fournisseur()->with('comptaGen', 'comptaFournisseur')->first();
        $this->supply = $supply;
        $this->form->fill();
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Editer le fournisseur')
            ->schema([
                Grid::make(2)
                    ->schema([
                        Toggle::make('tva')
                            ->label('Assujesti à la TVA')
                            ->live()
                            ->default($this->supply?->tva ?? false),

                        TextInput::make('num_tva')
                            ->label('Numero TVA')
                            ->default($this->supply?->num_tva)
                            ->live()
                            ->hidden(fn (Get $get): bool => ! $get('tva')),
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('code_comptable_general')
                            ->label('Code Comptable Générale')
                            ->options(fn () => PlanComptable::pluck('account', 'id'))
                            ->searchable()
                            ->default($this->supply?->code_comptable_general),

                        Select::make('code_comptable_fournisseur')
                            ->label('Code Comptable Fournisseur')
                            ->options(fn () => PlanComptable::pluck('account', 'id'))
                            ->searchable()
                            ->default($this->supply?->code_comptable_fournisseur),
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('condition_reglement_id')
                            ->label('Condition Reglement')
                            ->options(fn () => ConditionReglement::pluck('name', 'id'))
                            ->searchable()
                            ->default($this->supply?->condition_reglement_id),

                        Select::make('mode_reglement_id')
                            ->label('Mode de réglement')
                            ->options(fn () => ModeReglement::pluck('name', 'id'))
                            ->searchable()
                            ->default($this->supply?->mode_reglement_id),
                    ]),
            ])
            ->action(function (array $data): void {});
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.tab-supply');
    }
}

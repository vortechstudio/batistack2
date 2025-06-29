<?php

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TabTiers extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public Tiers $tiers;

    public ?array $data = [];

    public function mount(Tiers $tiers): void
    {
        $this->tiers = $tiers;
    }

    public function draftMailAction(): Action
    {
        return Action::make('draftMail')
            ->label('Envoyer un email')
            ->schema([

            ]);
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Modifier')
            ->url('/');
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->color('danger')
            ->icon('solar-trash-bin-2-bold-duotone')
            ->label('Supprimer')
            ->url('/');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Derniers Evènements')
            ->query(Tiers::query()->where('tiers.id', $this->tiers->id)->newQuery())
            ->columns([
                TextColumn::make('id')
                    ->label('# Ref'),

                TextColumn::make('title')
                    ->label('Titre'),
            ]);
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.tab-tiers');
    }
}

<?php

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersContact;
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

class TableContact extends Component implements HasForms, HasActions, HasTable
{
    use InteractsWithForms, InteractsWithActions, InteractsWithTable;
    public Tiers $tiers;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des contacts du tiers')
            ->headerActions([
                Action::make('createContact')
                    ->label('Ajouter un contact')
                    ->icon('heroicon-o-plus')
            ])
            ->query($this->tiers->contacts()->getQuery())
            ->columns([
                TextColumn::make('nom')
                ->label('Identité')
                ->formatStateUsing(fn(TiersContact $record) => $record->nom." ".$record->prenom),

                TextColumn::make('poste')
                ->label('Poste/Fonction'),

                TextColumn::make('tel')
                    ->label('Coordonnées')
                    ->formatStateUsing(fn(TiersContact $record) => "<div class='flex flex-col'><div class='mb-1'><strong>Téléphone: </strong>$record->tel</div><div class='mb-1'><strong>Portable: </strong>$record->portable</div><div class='mb-1'><strong>Email: </strong>$record->email</div></div>")
                    ->html(),
            ]);
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.table-contact');
    }
}

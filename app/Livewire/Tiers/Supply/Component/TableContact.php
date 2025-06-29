<?php

namespace App\Livewire\Tiers\Supply\Component;

use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersContact;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TableContact extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public Tiers $tiers;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des contacts du tiers')
            ->headerActions([
                Action::make('createContact')
                    ->label('Ajouter un contact')
                    ->icon('heroicon-o-plus')
                    ->schema([
                        Hidden::make('tiers_id')
                            ->default($this->tiers->id),

                        Grid::make()
                            ->columns(['lg' => 3])
                            ->schema([
                                Select::make('civilite')
                                    ->label('Civilité')
                                    ->options([
                                        'Mr' => 'Mr',
                                        'Mme' => 'Mme',
                                        'Mlle' => 'Mlle',
                                    ]),

                                TextInput::make('nom')
                                    ->label('Nom'),

                                TextInput::make('prenom')
                                    ->label('Prénom'),
                            ]),

                        Grid::make()
                            ->columns(['lg' => 3])
                            ->schema([
                                TextInput::make('telephone')
                                    ->label('Téléphone')
                                    ->prefixIcon('solar-phone-bold-duotone'),

                                TextInput::make('portable')
                                    ->label('Mobile')
                                    ->prefixIcon('solar-smartphone-2-bold-duotone'),

                                TextInput::make('email')
                                    ->label('Adresse email')
                                    ->prefixIcon('heroicon-c-at-symbol'),
                            ]),
                    ]),
            ])
            ->query($this->tiers->contacts()->getQuery())
            ->columns([
                TextColumn::make('nom')
                    ->label('Identité')
                    ->formatStateUsing(fn (TiersContact $record) => $record->nom.' '.$record->prenom),

                TextColumn::make('poste')
                    ->label('Poste/Fonction'),

                TextColumn::make('tel')
                    ->label('Coordonnées')
                    ->formatStateUsing(fn (TiersContact $record) => "<div class='flex flex-col'><div class='mb-1'><strong>Téléphone: </strong>$record->tel</div><div class='mb-1'><strong>Portable: </strong>$record->portable</div><div class='mb-1'><strong>Email: </strong>$record->email</div></div>")
                    ->html(),
            ])
            ->recordActions([
                Action::make('delete')
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (TiersContact $record) => $record->delete()),
            ]);
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.table-contact');
    }
}

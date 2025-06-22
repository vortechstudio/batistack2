<?php

namespace App\Livewire\Tiers;

use App\Enums\Tiers\TiersNature;
use App\Enums\Tiers\TiersType;
use App\Helpers\Helpers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

class CreateForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public string $type;
    public ?array $data = [];

    public function mount(string $type): void
    {
        $this->type = $type;
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        if ($this->type == 'supply') {
            return $schema
                ->components([
                    Wizard::make([
                        Wizard\Step::make('Informations')
                            ->description('Informations sur le fournisseur')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Raison Sociale')
                                    ->required(),

                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('type')
                                            ->label('Type de Tiers')
                                            ->options(TiersType::array()),

                                        TextInput::make('code_tiers')
                                            ->label('Code Fournisseur')
                                            ->default(Helpers::generateCodeTiers('f')),
                                    ]),

                                TextInput::make('siren')
                                    ->label('Siren')
                                    ->required(),

                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('tva')
                                            ->label('Assujeti à la TVA')
                                            ->live(),

                                        TextInput::make('num_tva')
                                            ->label('Numéro de TVA')
                                            ->hidden(fn(Get $get) => !$get('tva')),
                                    ]),

                            ])
                    ])
                ])
                ->statePath('data');
        } else {
            return $schema;
        }
    }

    public function render()
    {
        return view('livewire.tiers.create-form');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Tiers;

use App\Enums\Tiers\TiersType;
use App\Helpers\Helpers;
use App\Models\Core\Bank;
use App\Models\Core\City;
use App\Models\Core\ConditionReglement;
use App\Models\Core\Country;
use App\Models\Core\ModeReglement;
use App\Models\Core\PlanComptable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Intervention\Validation\Rules\Bic;
use Intervention\Validation\Rules\Iban;
use Livewire\Component;

final class CreateForm extends Component implements HasSchemas
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
        if ($this->type === 'supply') {
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
                                            ->hidden(fn(Get $get): bool => !$get('tva')),
                                    ]),

                            ]),

                        Wizard\Step::make('Adresse Postal')
                            ->description('Adresse postale du fournisseur')
                            ->schema([
                                Textarea::make('address')
                                    ->label('Adresse'),

                                Grid::make()
                                    ->columns(5)
                                    ->schema([
                                        TextInput::make('code_postal')
                                            ->columnSpan(1)
                                            ->label('Code postal'),

                                        Select::make('ville')
                                            ->columnSpan(2)
                                            ->label('Ville')
                                            ->searchable()
                                            ->options(fn(Get $get) => $get('code_postal') ? City::where('postal_code', $get('code_postal'))->pluck('city', 'city')->toArray() : City::all()->pluck('city', 'city')->toArray()),

                                        Select::make('pays')
                                            ->columnSpan(2)
                                            ->label('Pays')
                                            ->searchable()
                                            ->options(fn() => Country::all()->pluck('name', 'name')->toArray()),
                                    ]),
                            ]),

                        Wizard\Step::make('Contact')
                            ->description('Contact Principale du fournisseur')
                            ->schema([
                                Grid::make(7)
                                    ->schema([
                                        Select::make('civilite')
                                            ->label('Civilite')
                                            ->columnSpan(1)
                                            ->options([
                                                'Mr' => 'Mr',
                                                'Mme' => 'Mme',
                                                'Mlle' => 'Mlle',
                                            ]),

                                        TextInput::make('nom')
                                            ->columnSpan(2)
                                            ->label('Nom'),

                                        TextInput::make('prenom')
                                            ->columnSpan(2)
                                            ->label('Prenom'),

                                        TextInput::make('poste')
                                            ->columnSpan(2)
                                            ->label('Poste'),
                                    ]),

                                Grid::make(6)
                                    ->schema([
                                        TextInput::make('tel')
                                            ->prefixIcon(Heroicon::Phone)
                                            ->columnSpan(2)
                                            ->label('Téléphone'),

                                        TextInput::make('portable')
                                            ->prefixIcon(Heroicon::Phone)
                                            ->columnSpan(2)
                                            ->label('Portable'),

                                        TextInput::make('email')
                                            ->prefixIcon(Heroicon::AtSymbol)
                                            ->columnSpan(2)
                                            ->label('Email'),
                                    ]),
                            ]),

                        Wizard\Step::make('Fournisseur')
                            ->description('Information dédié au fournisseur')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('code_comptable_general')
                                            ->label('Code Comptable (Général)')
                                            ->columnSpan(1)
                                            ->searchable()
                                            ->getSearchResultsUsing(fn(string $search): array => PlanComptable::query()->whereLike('account', "%" . $search . "%")->limit(25)->pluck('account', 'id')->all())
                                            ->getOptionLabelUsing(fn($value): ?string => PlanComptable::find($value)->code . ' - ' . PlanComptable::find($value)->account)
                                            ->allowHtml(),

                                        TextInput::make('code_comptable_fournisseur')
                                            ->label('Code Comptable (Fournisseur)')
                                            ->columnSpan(1),
                                    ]),

                                Grid::make()
                                    ->schema([
                                        Select::make('condition_reglement_id')
                                            ->label('Condition Reglement')
                                            ->columnSpan(1)
                                            ->options(fn() => ConditionReglement::all()->pluck('name', 'id')->all()),

                                        Select::make('mode_reglement_id')
                                            ->label('Mode de Règlement')
                                            ->columnSpan(1)
                                            ->options(fn() => ModeReglement::all()->pluck('name', 'id')->all()),
                                    ]),
                            ]),

                        Wizard\Step::make('Banque')
                            ->description("Information bancaire")
                            ->schema([
                                Select::make('bank_id')
                                    ->label('Banque')
                                    ->searchable()
                                    ->getSearchResultsUsing(fn(string $search): array => Bank::query()->whereLike('name', "%" . $search . "%")->limit(25)->pluck('name', 'id')->all())
                                    ->getOptionLabelUsing(function (string $value) {
                                        return "<div class='flex flex-row items-center'><div class='avatar'><div class='w-8 rounded'><img src='" . Bank::find($value)->logo . "' alt='" . Bank::find($value)->logo . "'></div></div><div class='flex flex-col'><span>" . Bank::find($value)->name . "</span></div></div>";
                                    })
                                    ->allowHtml(),

                                Grid::make()
                                    ->schema([
                                        TextInput::make('iban')
                                            ->label('IBAN')
                                            ->rules([new Iban()]),

                                        TextInput::make('bic')
                                            ->label('BIC')
                                            ->rules([new Bic()]),
                                    ])
                            ])
                    ])
                        ->submitAction(new HtmlString('<button type="submit" class="btn btn-sm btn-primary">Créer un tiers</button>'))
                ])
                ->statePath('data');
        }

        return $schema;

    }

    public function create()
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.tiers.create-form');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Tiers;

use App\Enums\Tiers\TiersNature;
use App\Enums\Tiers\TiersType;
use App\Helpers\Helpers;
use App\Models\Core\Bank;
use App\Models\Core\City;
use App\Models\Core\ConditionReglement;
use App\Models\Core\Country;
use App\Models\Core\ModeReglement;
use App\Models\Core\PlanComptable;
use App\Models\Tiers\Tiers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Intervention\Validation\Rules\Bic;
use Intervention\Validation\Rules\Iban;
use Livewire\Component;

final class CreateForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public string $type;

    public ?array $data = [];
    public ?Tiers $tiers;

    public function mount(string $type, int|null $id = null): void
    {
        $this->type = $type;

        if ($id) {
            $this->tiers = Tiers::find($id);
            $this->form->fill($this->tiers->attributesToArray());
        } else {
            $this->form->fill();
        }
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
                                            ->getSearchResultsUsing(fn(string $search): array => PlanComptable::query()->whereLike('account', '%' . $search . '%')->limit(25)->pluck('account', 'id')->all())
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
                            ->description('Information bancaire')
                            ->schema([
                                Select::make('bank_id')
                                    ->label('Banque')
                                    ->searchable()
                                    ->options(Bank::all()->pluck('name', 'id')->toArray()),

                                Grid::make()
                                    ->schema([
                                        TextInput::make('iban')
                                            ->label('IBAN')
                                            ->rules([new Iban()]),

                                        TextInput::make('bic')
                                            ->label('BIC')
                                            ->rules([new Bic()]),
                                    ]),
                            ]),
                    ])
                        ->submitAction(new HtmlString('<button type="submit" class="btn btn-sm btn-primary">Créer un tiers</button>')),
                ])
                ->statePath('data');
        }

        return $schema;

    }

    public function create()
    {
        Tiers::create([
            'name' => $this->form->getState()['name'],
            'nature' => $this->type == 'supply' ? TiersNature::Fournisseur : TiersNature::Client,
            'type' => $this->form->getState()['type'],
            'code_tiers' => $this->form->getState()['code_tiers'],
            'siren' => $this->form->getState()['siren'],
            'tva' => $this->form->getState()['tva'],
            'num_tva' => $this->form->getState()['tva'] ? $this->form->getState()['num_tva'] : null,
        ]);
        $tiers = Tiers::orderBy('id', 'desc')->first();

        $tiers->addresses()->create([
            'address' => $this->form->getState()['address'],
            'code_postal' => $this->form->getState()['code_postal'],
            'ville' => $this->form->getState()['ville'],
            'pays' => $this->form->getState()['pays'],
            'tiers_id' => $tiers->id,
        ]);

        if (isset($this->form->getState()['civilite']) || isset($this->form->getState()['nom']) || isset($this->form->getState()['prenom'])) {
            $tiers->contacts()->create([
                'tiers_id' => $tiers->id,
                'civilite' => $this->form->getState()['civilite'],
                'nom' => $this->form->getState()['nom'],
                'prenom' => $this->form->getState()['prenom'],
                'poste' => $this->form->getState()['poste'],
                'tel' => $this->form->getState()['tel'],
                'portable' => $this->form->getState()['portable'],
                'email' => $this->form->getState()['email'],
            ]);
        }

        if ($this->type == 'supply') {
            $four = $tiers->fournisseur()->create([
                'tiers_id' => $tiers->id,
                'tva' => $this->form->getState()['tva'],
                'num_tva' => $this->form->getState()['tva'] ? $this->form->getState()['num_tva'] : null,
                'code_comptable_general' => $this->form->getState()['code_comptable_general'],
                'code_comptable_fournisseur' => $this->form->getState()['code_comptable_general'],
                'condition_reglement_id' => $this->form->getState()['condition_reglement_id'],
                'mode_reglement_id' => $this->form->getState()['mode_reglement_id'],
            ]);

            PlanComptable::updateOrCreate([
                'code' => $this->form->getState()['code_comptable_fournisseur']
            ], [
                'code' => $this->form->getState()['code_comptable_fournisseur'],
                'account' => $this->form->getState()['name'],
                'type' => 'Payable',
                'lettrage' => 1,
                'principal' => 4,
                'initial' => 0
            ]);
            $plan = PlanComptable::where('code', $this->form->getState()['code_comptable_fournisseur'])->first();

            $four->update([
                'code_comptable_fournisseur' => $plan->id,
            ]);
        } else {
            $clt = $tiers->client()->create([
                'tiers_id' => $tiers->id,
                'tva' => $this->form->getState()['tva'],
                'num_tva' => $this->form->getState()['tva'] ? $this->form->getState()['num_tva'] : null,
                'code_comptable_general' => $this->form->getState()['code_comptable_general'],
                'code_comptable_client' => $this->form->getState()['code_comptable_general'],
                'condition_reglement_id' => $this->form->getState()['condition_reglement_id'],
                'mode_reglement_id' => $this->form->getState()['mode_reglement_id'],
            ]);

            PlanComptable::updateOrCreate([
                'code' => $this->form->getState()['code_comptable_client']
            ], [
                'code' => $this->form->getState()['code_comptable_client'],
                'account' => $this->form->getState()['name'],
                'type' => 'Client',
                'lettrage' => 1,
                'principal' => 4,
                'initial' => 0
            ]);
            $plan = PlanComptable::where('code', $this->form->getState()['code_comptable_client'])->first();
            $clt->update([
                'code_comptable_client' => $plan->id,
            ]);
        }

        if (isset($this->form->getState()['bank_id'])) {
            $tiers->banks()->create([
                'iban' => $this->form->getState()['iban'],
                'bic' => $this->form->getState()['bic'],
                'tiers_id' => $tiers->id,
                'bank_id' => $this->form->getState()['bank_id'],
                'default' => false,
            ]);
        }

        Notification::make()
            ->title("Création d'un tier")
            ->body(Str::markdown("Le tiers **$tiers->name** à été créer avec succès"))
            ->icon(Heroicon::CheckCircle)
            ->iconColor('success')
            ->send();

        $this->redirect(route('tiers.supply.list'));

    }

    public function render()
    {
        return view('livewire.tiers.create-form');
    }
}

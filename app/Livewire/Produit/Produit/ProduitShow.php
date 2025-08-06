<?php

namespace App\Livewire\Produit\Produit;

use App\Enums\Produits\TauxTVA;
use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Models\Core\PlanComptable;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\TarifClient;
use App\Models\Produit\TarifFournisseur;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProduitShow extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;
    public Produit $produit;

    public function mount(int $id)
    {
        $this->produit = Produit::findOrFail($id);
    }

    public function editAction(): EditAction
    {
        return EditAction::make('edit')
            ->record($this->produit) // Ajouter cette ligne pour spécifier le modèle à éditer
            ->schema([
                Wizard::make([
                            Step::make('Produit')
                                ->schema([
                                    Grid::make(4)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Libellé')
                                                ->required(),

                                            TextInput::make('serial_number')
                                                ->label('Numéro de série'),

                                            Checkbox::make('achat')
                                                ->label("Disponible à l'achat"),

                                            Checkbox::make('vente')
                                                ->label('Disponible à la vente'),
                                        ]),

                                    Grid::make(3)
                                        ->schema([
                                            Select::make('category_id')
                                                ->label('Catégorie')
                                                ->options(Category::query()->pluck('name', 'id'))
                                                ->required(),

                                            Select::make('entrepot_id')
                                                ->label('Entrepôt')
                                                ->options(Entrepot::query()->where('status', 1)->pluck('name', 'id'))
                                                ->required(),

                                            Select::make('code_comptable_vente')
                                                ->label('Code comptable de vente')
                                                ->options(
                                                    PlanComptable::query()
                                                        ->where('type', 'Revenus')
                                                        ->get()
                                                        ->mapWithKeys(function ($planComptable) {
                                                            return [$planComptable->id => $planComptable->code.' - '.$planComptable->account];
                                                        })
                                                        ->toArray()
                                                )
                                                ->searchable(),
                                        ]),

                                    RichEditor::make('description')
                                        ->label('Description'),

                                    Section::make('Poids')
                                        ->schema([
                                            Grid::make()
                                                ->schema([
                                                    TextInput::make('poids_value')
                                                        ->label('Poids'),

                                                    Select::make('poids_unite')
                                                        ->label('Unité')
                                                        ->options(UnitePoids::getSelectOptions()),
                                                ]),
                                        ]),

                                    Section::make('Dimension')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('longueur')
                                                        ->label('longueur'),

                                                    TextInput::make('largeur')
                                                        ->label('Largeur'),

                                                    TextInput::make('hauteur')
                                                        ->label('Hauteur'),

                                                    Select::make('llh_unite')
                                                        ->label('Unité')
                                                        ->options(UniteMesure::getSelectOptions()),
                                                ]),
                                        ]),
                                ]),

                            Step::make('Tarification')
                                ->schema([
                                    Repeater::make('tarifClient')
                                        ->label('Tarifs Clients')
                                        ->schema([
                                            TextInput::make('prix_unitaire')
                                                ->label('Prix unitaire')
                                                ->required(),

                                            Select::make('taux_tva')
                                                ->label('Taux TVA')
                                                ->options(TauxTVA::getSelectOptions())
                                                ->required(),
                                        ])
                                        ->columns(2),

                                    Repeater::make('tarifFournisseur')
                                        ->label('Tarifs Fournisseur')
                                        ->schema([
                                            TextInput::make('ref_fournisseur')
                                                ->label('Référence')
                                                ->required(),

                                            TextInput::make('qte_minimal')
                                                ->label('Quantité minimal')
                                                ->default(1),

                                            TextInput::make('prix_unitaire')
                                                ->label('Prix unitaire'),

                                            TextInput::make('delai_livraison')
                                                ->label('Delai de livraison')
                                                ->default(1)
                                                ->suffix('Jours'),

                                            TextInput::make('barrecode')
                                                ->label('Code barre'),
                                        ])
                                        ->columns(5),
                                ]),

                            Step::make('Stock')
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            TextInput::make('limit_stock')
                                                ->label('Seuil de stock')
                                                ->default(10),

                                            TextInput::make('optimal_stock')
                                                ->label('Stock optimal'),
                                        ]),

                                    Repeater::make('stockInitial')
                                        ->label('Stock Initial')
                                        ->schema([
                                            Select::make('entrepot_id')
                                                ->label('Entrepôt')
                                                ->options(Entrepot::query()->where('status', 1)->pluck('name', 'id'))
                                                ->required(),

                                            TextInput::make('quantite')
                                                ->label('Quantité')
                                                ->default(0),
                                        ])
                                        ->columns(2),
                                ]),
                        ]),
            ])
            ->using(function (array $data) {
                // Mise à jour des données principales du produit
                $this->produit->update([
                    'name' => $data['name'],
                    'serial_number' => $data['serial_number'] ?? null,
                    'achat' => $data['achat'] ?? false,
                    'vente' => $data['vente'] ?? false,
                    'category_id' => $data['category_id'],
                    'entrepot_id' => $data['entrepot_id'],
                    'code_comptable_vente' => $data['code_comptable_vente'] ?? null,
                    'description' => $data['description'] ?? null,
                    'poids_value' => $data['poids_value'] ?? null,
                    'poids_unite' => $data['poids_unite'] ?? null,
                    'longueur' => $data['longueur'] ?? null,
                    'largeur' => $data['largeur'] ?? null,
                    'hauteur' => $data['hauteur'] ?? null,
                    'llh_unite' => $data['llh_unite'] ?? null,
                    'limit_stock' => $data['limit_stock'] ?? null,
                    'optimal_stock' => $data['optimal_stock'] ?? null,
                ]);

                // Gestion des tarifs clients
                if (isset($data['tarifClient']) && is_array($data['tarifClient'])) {
                    // Supprimer les anciens tarifs clients
                    $this->produit->tarifsClient()->delete();

                    // Créer les nouveaux tarifs clients
                    foreach ($data['tarifClient'] as $tarifData) {
                        if (!empty($tarifData['prix_unitaire']) && !empty($tarifData['taux_tva'])) {
                            TarifClient::create([
                                'produit_id' => $this->produit->id,
                                'prix_unitaire' => (float) $tarifData['prix_unitaire'],
                                'taux_tva' => (float) $tarifData['taux_tva'],
                            ]);
                        }
                    }
                }

                // Gestion des tarifs fournisseurs
                if (isset($data['tarifFournisseur']) && is_array($data['tarifFournisseur'])) {
                    // Supprimer les anciens tarifs fournisseurs
                    $this->produit->tarifsFournisseur()->delete();

                    // Créer les nouveaux tarifs fournisseurs
                    foreach ($data['tarifFournisseur'] as $tarifData) {
                        if (!empty($tarifData['ref_fournisseur'])) {
                            TarifFournisseur::create([
                                'produit_id' => $this->produit->id,
                                'ref_fournisseur' => $tarifData['ref_fournisseur'],
                                'qte_minimal' => (float) ($tarifData['qte_minimal'] ?? 1),
                                'prix_unitaire' => (float) ($tarifData['prix_unitaire'] ?? 0),
                                'delai_livraison' => (int) ($tarifData['delai_livraison'] ?? 1),
                                'barrecode' => $tarifData['barrecode'] ?? null,
                            ]);
                        }
                    }
                }

                // Gestion du stock initial
                if (isset($data['stockInitial']) && is_array($data['stockInitial'])) {
                    foreach ($data['stockInitial'] as $stockData) {
                        if (!empty($stockData['entrepot_id'])) {
                            // Vérifier si un stock existe déjà pour ce produit et cet entrepôt
                            $existingStock = ProduitStock::where('produit_id', $this->produit->id)
                                ->where('entrepot_id', $stockData['entrepot_id'])
                                ->first();

                            if ($existingStock) {
                                // Mettre à jour le stock existant
                                $existingStock->update([
                                    'quantite' => (int) ($stockData['quantite'] ?? 0),
                                ]);
                            } else {
                                // Créer un nouveau stock
                                ProduitStock::create([
                                    'produit_id' => $this->produit->id,
                                    'entrepot_id' => $stockData['entrepot_id'],
                                    'quantite' => (int) ($stockData['quantite'] ?? 0),
                                ]);
                            }
                        }
                    }
                }

                // Recharger le produit avec ses relations pour refléter les changements
                $this->produit->load(['tarifsClient', 'tarifsFournisseur', 'stocks']);

                // Notification de succès
                \Filament\Notifications\Notification::make()
                    ->title('Produit mis à jour')
                    ->body('Le produit a été mis à jour avec succès.')
                    ->success()
                    ->send();
            });
    }

    #[Title('Détail du produit')]
    #[Layout('components.layouts.produit')]
    public function render()
    {
        return view('livewire.produit.produit.produit-show');
    }
}

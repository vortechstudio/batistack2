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
use Purifier;
use Illuminate\Support\Facades\DB; // Ajout de l'import DB
use Illuminate\Support\Facades\Log; // Ajout pour le logging des erreurs
use Mews\Purifier\Facades\Purifier as FacadesPurifier;

class ProduitShow extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;
    public Produit $produit;

    public function mount(int $id)
    {
        $this->produit = Produit::with(['tarifsClient', 'tarifsFournisseur', 'stocks'])->findOrFail($id);
    }

    public function editAction(): EditAction
    {
        return EditAction::make('edit')
            ->record($this->produit)
            ->fillForm(function () {
                return [
                    'name' => $this->produit->name,
                    'serial_number' => $this->produit->serial_number,
                    'achat' => $this->produit->achat,
                    'vente' => $this->produit->vente,
                    'category_id' => $this->produit->category_id,
                    'entrepot_id' => $this->produit->entrepot_id,
                    'code_comptable_vente' => $this->produit->code_comptable_vente,
                    'description' => $this->produit->description,
                    'poids_value' => $this->produit->poids_value,
                    'poids_unite' => $this->produit->poids_unite,
                    'longueur' => $this->produit->longueur,
                    'largeur' => $this->produit->largeur,
                    'hauteur' => $this->produit->hauteur,
                    'llh_unite' => $this->produit->llh_unite,
                    'limit_stock' => $this->produit->limit_stock,
                    'optimal_stock' => $this->produit->optimal_stock,
                    'tarifClient' => $this->produit->tarifsClient->map(function ($tarif) {
                        return [
                            'prix_unitaire' => $tarif->prix_unitaire,
                            'taux_tva' => $tarif->taux_tva,
                        ];
                    })->toArray(),
                    'tarifFournisseur' => $this->produit->tarifsFournisseur->map(function ($tarif) {
                        return [
                            'ref_fournisseur' => $tarif->ref_fournisseur,
                            'qte_minimal' => $tarif->qte_minimal,
                            'prix_unitaire' => $tarif->prix_unitaire,
                            'delai_livraison' => $tarif->delai_livraison,
                            'barrecode' => $tarif->barrecode,
                        ];
                    })->toArray(),
                    'stockInitial' => $this->produit->stocks->map(function ($stock) {
                        return [
                            'entrepot_id' => $stock->entrepot_id,
                            'quantite' => $stock->quantite,
                        ];
                    })->toArray(),
                ];
            })
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
                                        ->label('Description')
                                        ->maxLength(5000),

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
                try {
                    // Démarrer une transaction pour assurer la cohérence des données
                    DB::transaction(function () use ($data) {
                        // Sanitisation de la description avant sauvegarde
                        if (isset($data['description'])) {
                            $data['description'] = FacadesPurifier::clean($data['description'], [
                                'HTML.Allowed' => 'p,br,strong,em,u,ol,ul,li,h1,h2,h3,h4,h5,h6',
                                'HTML.ForbiddenElements' => 'script,style,iframe,object,embed,form,input,button',
                                'Attr.AllowedFrameTargets' => [],
                                'HTML.SafeIframe' => false,
                                'URI.DisableExternalResources' => true,
                            ]);
                        }

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

                        // Gestion des tarifs clients avec validation
                        if (isset($data['tarifClient']) && is_array($data['tarifClient'])) {
                            // Supprimer les anciens tarifs clients
                            $this->produit->tarifsClient()->delete();

                            // Créer les nouveaux tarifs clients avec validation
                            foreach ($data['tarifClient'] as $tarifData) {
                                if (!empty($tarifData['prix_unitaire']) && !empty($tarifData['taux_tva'])) {
                                    // Validation des données avant création
                                    $prixUnitaire = (float) $tarifData['prix_unitaire'];
                                    $tauxTva = (float) $tarifData['taux_tva'];

                                    if ($prixUnitaire < 0) {
                                        throw new \InvalidArgumentException('Le prix unitaire ne peut pas être négatif');
                                    }

                                    if ($tauxTva < 0 || $tauxTva > 100) {
                                        throw new \InvalidArgumentException('Le taux TVA doit être entre 0 et 100');
                                    }

                                    TarifClient::create([
                                        'produit_id' => $this->produit->id,
                                        'prix_unitaire' => $prixUnitaire,
                                        'taux_tva' => $tauxTva,
                                    ]);
                                }
                            }
                        }

                        // Gestion des tarifs fournisseurs avec validation
                        if (isset($data['tarifFournisseur']) && is_array($data['tarifFournisseur'])) {
                            // Supprimer les anciens tarifs fournisseurs
                            $this->produit->tarifsFournisseur()->delete();

                            // Créer les nouveaux tarifs fournisseurs avec validation
                            foreach ($data['tarifFournisseur'] as $tarifData) {
                                if (!empty($tarifData['ref_fournisseur'])) {
                                    // Validation des données
                                    $qteMinimal = (float) ($tarifData['qte_minimal'] ?? 1);
                                    $prixUnitaire = (float) ($tarifData['prix_unitaire'] ?? 0);
                                    $delaiLivraison = (int) ($tarifData['delai_livraison'] ?? 1);

                                    if ($qteMinimal < 0) {
                                        throw new \InvalidArgumentException('La quantité minimale ne peut pas être négative');
                                    }

                                    if ($prixUnitaire < 0) {
                                        throw new \InvalidArgumentException('Le prix unitaire ne peut pas être négatif');
                                    }

                                    if ($delaiLivraison < 0) {
                                        throw new \InvalidArgumentException('Le délai de livraison ne peut pas être négatif');
                                    }

                                    TarifFournisseur::create([
                                        'produit_id' => $this->produit->id,
                                        'ref_fournisseur' => $tarifData['ref_fournisseur'],
                                        'qte_minimal' => $qteMinimal,
                                        'prix_unitaire' => $prixUnitaire,
                                        'delai_livraison' => $delaiLivraison,
                                        'barrecode' => $tarifData['barrecode'] ?? null,
                                    ]);
                                }
                            }
                        }

                        // Gestion du stock initial avec validation
                        if (isset($data['stockInitial']) && is_array($data['stockInitial'])) {
                            foreach ($data['stockInitial'] as $stockData) {
                                if (!empty($stockData['entrepot_id'])) {
                                    $quantite = (int) ($stockData['quantite'] ?? 0);

                                    // Validation de la quantité
                                    if ($quantite < 0) {
                                        throw new \InvalidArgumentException('La quantité ne peut pas être négative');
                                    }

                                    // Vérifier si l'entrepôt existe et est actif
                                    $entrepotExists = Entrepot::where('id', $stockData['entrepot_id'])
                                        ->where('status', 1)
                                        ->exists();

                                    if (!$entrepotExists) {
                                        throw new \InvalidArgumentException('L\'entrepôt spécifié n\'existe pas ou n\'est pas actif');
                                    }

                                    // Vérifier si un stock existe déjà pour ce produit et cet entrepôt
                                    $existingStock = ProduitStock::where('produit_id', $this->produit->id)
                                        ->where('entrepot_id', $stockData['entrepot_id'])
                                        ->first();

                                    if ($existingStock) {
                                        // Mettre à jour le stock existant
                                        $existingStock->update([
                                            'quantite' => $quantite,
                                        ]);
                                    } else {
                                        // Créer un nouveau stock
                                        ProduitStock::create([
                                            'produit_id' => $this->produit->id,
                                            'entrepot_id' => $stockData['entrepot_id'],
                                            'quantite' => $quantite,
                                        ]);
                                    }
                                }
                            }
                        }

                        // Recharger le produit avec ses relations pour refléter les changements
                        $this->produit->load(['tarifsClient', 'tarifsFournisseur', 'stocks']);
                    });

                    // Notification de succès (en dehors de la transaction)
                    \Filament\Notifications\Notification::make()
                        ->title('Produit mis à jour')
                        ->body('Le produit a été mis à jour avec succès.')
                        ->success()
                        ->send();

                } catch (\InvalidArgumentException $e) {
                    // Erreur de validation des données
                    Log::warning('Erreur de validation lors de la mise à jour du produit', [
                        'produit_id' => $this->produit->id,
                        'error' => $e->getMessage(),
                        'data' => $data
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Erreur de validation')
                        ->body('Erreur de validation : ' . $e->getMessage())
                        ->danger()
                        ->send();

                    throw $e; // Re-lancer l'exception pour arrêter le processus

                } catch (\Exception $e) {
                    // Erreur générale (base de données, etc.)
                    Log::error('Erreur lors de la mise à jour du produit', [
                        'produit_id' => $this->produit->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'data' => $data
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Erreur de mise à jour')
                        ->body('Une erreur est survenue lors de la mise à jour. Veuillez réessayer.')
                        ->danger()
                        ->send();

                    throw $e; // Re-lancer l'exception pour que Filament puisse la gérer
                }
            });
    }

    #[Title('Détail du produit')]
    #[Layout('components.layouts.produit')]
    public function render()
    {
        return view('livewire.produit.produit.produit-show');
    }
}

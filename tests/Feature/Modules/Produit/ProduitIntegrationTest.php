<?php

declare(strict_types=1);

use App\Actions\Produit\NewProduct;
use App\Livewire\Produit\Components\Table\TableProduit;
use App\Livewire\Produit\Produit\ProduitShow;
use App\Models\Core\PlanComptable;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\TarifClient;
use App\Models\Produit\TarifFournisseur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Intégration complète du module Produits', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create();
        $this->entrepot = Entrepot::factory()->actif()->create(); // Forcer l'entrepôt à être actif
        $this->planComptable = PlanComptable::create([
            'type' => 'Revenus',
            'code' => '701',
            'account' => 'Ventes de marchandises',
            'lettrage' => false,
        ]);
    });

    test('workflow complet de création d\'un produit avec tarifs et stock', function () {
        // Vérifier que les données de base existent
        expect($this->category)->not->toBeNull();
        expect($this->entrepot)->not->toBeNull();
        expect($this->entrepot->status)->toBeTrue(); // Vérifier que l'entrepôt est actif
        expect($this->planComptable)->not->toBeNull();

        // Utiliser directement l'action NewProduct au lieu du formulaire Filament
        $data = [
            'name' => 'Produit Intégration Test',
            'serial_number' => 'INT-001',
            'achat' => true,
            'vente' => true,
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'code_comptable_vente' => $this->planComptable->id,
            'description' => 'Produit pour test d\'intégration',
            'poids_value' => 2.5,
            'poids_unite' => 'kg',
            'longueur' => 100,
            'largeur' => 50,
            'hauteur' => 25,
            'llh_unite' => 'mm',
            'limit_stock' => 10,
            'optimal_stock' => 30,
            'tarifClient' => [
                [
                    'prix_unitaire' => 99.99,
                    'taux_tva' => 20.0,
                ],
            ],
            'tarifFournisseur' => [
                [
                    'ref_fournisseur' => 'FOURNISSEUR-001',
                    'qte_minimal' => 1,
                    'prix_unitaire' => 75.00,
                    'delai_livraison' => 7,
                    'barrecode' => '1234567890123',
                ],
            ],
            'stockInitial' => [
                [
                    'entrepot_id' => $this->entrepot->id,
                    'quantite' => 100,
                ],
            ],
        ];

        // Appeler directement l'action NewProduct
        $produit = app(NewProduct::class)->handle($data);

        // Vérifier la création du produit
        expect($produit)->not->toBeNull()
            ->and($produit->name)->toBe('Produit Intégration Test')
            ->and($produit->category_id)->toBe($this->category->id)
            ->and($produit->entrepot_id)->toBe($this->entrepot->id);

        // Vérifier les tarifs client
        $tarifClient = TarifClient::where('produit_id', $produit->id)->first();
        expect($tarifClient)->not->toBeNull()
            ->and($tarifClient->prix_unitaire)->toBe(99.99) // Float au lieu de string
            ->and($tarifClient->taux_tva)->toBe(20.0); // Float au lieu de string

        // Vérifier les tarifs fournisseur
        $tarifFournisseur = TarifFournisseur::where('produit_id', $produit->id)->first();
        expect($tarifFournisseur)->not->toBeNull()
            ->and($tarifFournisseur->ref_fournisseur)->toBe('FOURNISSEUR-001');

        // Vérifier le stock initial
        $stock = ProduitStock::where('produit_id', $produit->id)->first();
        expect($stock)->not->toBeNull()
            ->and($stock->quantite)->toBe(100);
    });

    test('workflow complet : création -> affichage -> modification', function () {
        // 1. Créer un produit via l'action
        $data = [
            'name' => 'Produit Workflow Test',
            'serial_number' => 'WF-001',
            'achat' => true,
            'vente' => true,
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'code_comptable_vente' => $this->planComptable->id,
            'description' => 'Test workflow complet',
            'poids_value' => 1.5,
            'poids_unite' => 'kg',
            'longueur' => 80,
            'largeur' => 40,
            'hauteur' => 20,
            'llh_unite' => 'mm',
            'limit_stock' => 5,
            'optimal_stock' => 20,
            'tarifClient' => [
                [
                    'prix_unitaire' => 50.00,
                    'taux_tva' => 20.0,
                ],
            ],
            'stockInitial' => [
                [
                    'entrepot_id' => $this->entrepot->id,
                    'quantite' => 25,
                ],
            ],
        ];

        $produit = app(NewProduct::class)->handle($data);

        // 2. Vérifier l'affichage dans ProduitShow
        $showComponent = Livewire::test(ProduitShow::class, ['id' => $produit->id]);
        $showComponent->assertOk()
            ->assertSee('Produit Workflow Test')
            ->assertSee('WF-001')
            ->assertSee('Test workflow complet');

        // 3. Modifier le produit via ProduitShow
        $updateData = [
            'name' => 'Produit Workflow Modifié',
            'serial_number' => 'WF-001-MOD',
            'achat' => false,
            'vente' => true,
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'code_comptable_vente' => $this->planComptable->id,
            'description' => 'Description modifiée du workflow',
            'poids_value' => 2.0,
            'poids_unite' => 'kg',
            'longueur' => 100,
            'largeur' => 50,
            'hauteur' => 25,
            'llh_unite' => 'mm',
            'limit_stock' => 8,
            'optimal_stock' => 25,
        ];

        $showComponent->callAction('edit', $updateData);

        // 4. Vérifier les modifications
        $produit->refresh();
        expect($produit->name)->toBe('Produit Workflow Modifié')
            ->and($produit->serial_number)->toBe('WF-001-MOD')
            ->and($produit->achat)->toBeFalse()
            ->and($produit->limit_stock)->toBe(8.0)
            ->and($produit->description)->toBe('Description modifiée du workflow');

        // Vérifier que le produit existe toujours avec ses relations de base
        expect($produit->category_id)->toBe($this->category->id)
            ->and($produit->entrepot_id)->toBe($this->entrepot->id);
    });

    test('affichage correct dans le dashboard après création', function () {
        $produit = Produit::factory()->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'limit_stock' => 10,    // Seuil limite
            'optimal_stock' => 30,   // Seuil optimal
        ]);

        TarifClient::factory()->create([
            'produit_id' => $produit->id,
            'prix_unitaire' => 99.99,
        ]);

        ProduitStock::factory()->create([
            'produit_id' => $produit->id,
            'entrepot_id' => $this->entrepot->id,
            'quantite' => 50,  // Supérieur à optimal_stock (30) = "Normal"
        ]);

        // Vérifier l'affichage dans le widget tableau
        Livewire::test('produit.components.widgets.dashboard-table-produit')
            ->assertSee($produit->reference)
            ->assertSee($produit->name)
            ->assertSee('99,99')
            ->assertSee('Normal');

        // Vérifier les statistiques
        Livewire::test('produit.components.widgets.dashboard-stat-overview')
            ->assertSee('1'); // Total produits
    });

    test('intégration avec les widgets du dashboard', function () {
        // Créer plusieurs produits avec différents états
        $produits = collect();

        // Produit en stock normal
        $produits->push(Produit::factory()->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => true,
            'vente' => true,
            'limit_stock' => 10,
            'optimal_stock' => 30,
        ]));

        // Produit en rupture
        $produits->push(Produit::factory()->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => true,
            'vente' => false,
            'limit_stock' => 10,
            'optimal_stock' => 30,
        ]));

        // Créer des stocks et tarifs
        foreach ($produits as $index => $produit) {
            TarifClient::factory()->create([
                'produit_id' => $produit->id,
                'prix_unitaire' => 50.00 + ($index * 10),
            ]);

            ProduitStock::factory()->create([
                'produit_id' => $produit->id,
                'entrepot_id' => $this->entrepot->id,
                'quantite' => $index === 0 ? 50 : 5, // Premier en stock normal, second en rupture
            ]);
        }

        // Tester les widgets
        Livewire::test('produit.components.widgets.dashboard-stat-overview')
            ->assertSee('2') // Total produits
            ->assertSee('Total de produits');

        Livewire::test('produit.components.widgets.dashboard-table-produit')
            ->assertSee($produits[0]->reference)
            ->assertSee($produits[1]->reference);

        Livewire::test('produit.components.widgets.statistique-chart')
            ->assertSee('Statistiques des Produits');
    });

    test('gestion des erreurs lors de la création', function () {
        $component = Livewire::test(TableProduit::class);

        // Tenter de créer un produit avec des données invalides
        $component->callTableAction('create', data: [
            'name' => '', // Nom requis manquant
            'category_id' => 999, // Catégorie inexistante
            'entrepot_id' => 999, // Entrepôt inexistant
        ]);

        // Vérifier les erreurs de validation
        $component->assertHasTableActionErrors(['name', 'category_id', 'entrepot_id']);
    });

    test('navigation entre les composants', function () {
        $produit = Produit::factory()->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        // Test de navigation depuis l'index vers le show
        $indexComponent = Livewire::test('produit.produit.produit-index');
        $indexComponent->assertOk();

        // Test d'affichage du produit
        $showComponent = Livewire::test(ProduitShow::class, ['id' => $produit->id]);
        $showComponent->assertOk()
            ->assertSee($produit->name)
            ->assertSee($produit->reference);
    });
});

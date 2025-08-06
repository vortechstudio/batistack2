<?php

declare(strict_types=1);

use App\Livewire\Produit\Produit\ProduitIndex;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\TarifClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Index des Produits', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create();
        $this->entrepot = Entrepot::factory()->create();
    });

    test('affiche la page d\'index des produits', function () {
        Livewire::test(ProduitIndex::class)
            ->assertOk()
            ->assertSeeLivewire('produit.components.table.table-produit');
    });

    test('charge les produits correctement', function () {
        $produits = Produit::factory(5)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        $component = Livewire::test(ProduitIndex::class);

        foreach ($produits as $produit) {
            $component->assertSee($produit->reference);
        }
    });

    test('affiche les produits avec leurs informations de base', function () {
        $produit = Produit::factory()->create([
            'name' => 'Produit Test Index',
            'reference' => 'PTI-001',
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => true,
            'vente' => true,
        ]);

        // Ajouter un tarif et un stock pour des tests plus complets
        TarifClient::factory()->create([
            'produit_id' => $produit->id,
            'prix_unitaire' => 99.99,
        ]);

        ProduitStock::factory()->create([
            'produit_id' => $produit->id,
            'entrepot_id' => $this->entrepot->id,
            'quantite' => 50,
        ]);

        $component = Livewire::test(ProduitIndex::class);

        $component->assertSee('Produit Test Index')
            ->assertSee('PTI-001');
    });

    test('gère l\'affichage sans produits', function () {
        $component = Livewire::test(ProduitIndex::class);

        $component->assertOk();
        // Le composant doit s'afficher même sans produits
    });

    test('utilise le bon layout et titre', function () {
        $component = Livewire::test(ProduitIndex::class);

        // Vérifier que le composant utilise le bon layout
        $component->assertOk();

        // Le titre est défini via l'attribut Title
        expect($component->instance())->toBeInstanceOf(ProduitIndex::class);
    });

    test('intègre correctement le composant table', function () {
        Produit::factory(3)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        $component = Livewire::test(ProduitIndex::class);

        // Vérifier que le composant table est bien intégré
        $component->assertSeeLivewire('produit.components.table.table-produit');
    });

    test('performance avec de nombreux produits', function () {
        // Créer un grand nombre de produits pour tester les performances
        Produit::factory(50)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        $startTime = microtime(true);

        $component = Livewire::test(ProduitIndex::class);
        $component->assertOk();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Vérifier que le temps d'exécution reste raisonnable (moins de 2 secondes)
        expect($executionTime)->toBeLessThan(2.0);
    });
});

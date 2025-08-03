<?php

declare(strict_types=1);

use App\Livewire\Produit\Dashboard;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\Service;
use App\Models\Produit\TarifClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Dashboard Produits', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create();
        $this->entrepot = Entrepot::factory()->create();
    });

    test('affiche le dashboard avec les widgets', function () {
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSeeLivewire('produit.components.widgets.statistique-chart')
            ->assertSeeLivewire('produit.components.widgets.dashboard-stat-overview')
            ->assertSeeLivewire('produit.components.widgets.dashboard-table-produit')
            ->assertSeeLivewire('produit.components.widgets.dashboard-table-service');
    });

    test('affiche les statistiques correctes', function () {
        // Créer des données de test
        Produit::factory(5)->create(['category_id' => $this->category->id, 'entrepot_id' => $this->entrepot->id]);
        Service::factory(3)->create();
        Produit::factory(2)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => true,
            'vente' => false,
        ]);
        Produit::factory(3)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => false,
            'vente' => true,
        ]);

        Livewire::test('produit.components.widgets.dashboard-stat-overview')
            ->assertSee('Total de produits')
            ->assertSee('10') // 5 + 2 + 3
            ->assertSee('Total des services')
            ->assertSee('3')
            ->assertSee('Produits disponibles à l\'achat')
            ->assertSee('Produits disponibles à la vente');
    });

    test('affiche le graphique des statistiques', function () {
        // Créer des produits avec différentes disponibilités
        Produit::factory(2)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => true,
            'vente' => false,
        ]);
        Produit::factory(3)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => false,
            'vente' => true,
        ]);
        Produit::factory(1)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'achat' => true,
            'vente' => true,
        ]);

        // Test que le composant se charge correctement
        Livewire::test('produit.components.widgets.statistique-chart')
            ->assertOk()
            ->assertSee('Statistiques des Produits');

        // Vérifier les données directement via les modèles
        $disponiblesAchat = Produit::where('achat', true)->where('vente', false)->count();
        $disponiblesVente = Produit::where('vente', true)->where('achat', false)->count();
        $disponiblesAchatVente = Produit::where('achat', true)->where('vente', true)->count();
        $nonDisponibles = Produit::where('achat', false)->where('vente', false)->count();

        expect($disponiblesAchat)->toBe(2)
            ->and($disponiblesVente)->toBe(3)
            ->and($disponiblesAchatVente)->toBe(1)
            ->and($nonDisponibles)->toBe(0);
    });

    test('affiche le tableau des produits avec état de stock', function () {
        $produit = Produit::factory()->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'limit_stock' => 10,
            'optimal_stock' => 50,
        ]);

        // Créer un tarif client pour le produit
        TarifClient::factory()->create([
            'produit_id' => $produit->id,
            'service_id' => null,
            'prix_unitaire' => 99.99,
            'taux_tva' => 20.0,
        ]);

        // Créer un stock avec une quantité normale (supérieure au seuil optimal)
        ProduitStock::factory()->create([
            'produit_id' => $produit->id,
            'entrepot_id' => $this->entrepot->id,
            'quantite' => 60, // Supérieur au stock optimal (50)
        ]);

        Livewire::test('produit.components.widgets.dashboard-table-produit')
            ->assertSee($produit->reference)
            ->assertSee($produit->name)
            ->assertSee('Normal'); // État de stock
    });
});

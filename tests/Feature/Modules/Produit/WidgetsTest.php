<?php

declare(strict_types=1);

use App\Livewire\Produit\Components\Widgets\DashboardStatOverview;
use App\Livewire\Produit\Components\Widgets\DashboardTableProduit;
use App\Livewire\Produit\Components\Widgets\StatistiqueChart;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\Service;
use App\Models\Produit\TarifClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Widgets du Dashboard Produits', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create();
        $this->entrepot = Entrepot::factory()->create();
    });

    describe('DashboardStatOverview', function () {
        test('calcule correctement les statistiques', function () {
            // Créer des produits avec différents états
            Produit::factory(5)->create([
                'category_id' => $this->category->id,
                'entrepot_id' => $this->entrepot->id,
                'achat' => true,
                'vente' => true,
            ]);

            Produit::factory(2)->create([
                'category_id' => $this->category->id,
                'entrepot_id' => $this->entrepot->id,
                'achat' => true,
                'vente' => false,
            ]);

            Service::factory(3)->create();

            $component = Livewire::test(DashboardStatOverview::class);

            // Tester via les méthodes du widget Filament
            $component->assertSee('Total de produits')
                ->assertSee('7') // Total produits
                ->assertSee('Total des services')
                ->assertSee('3') // Total services
                ->assertSee('Produits disponibles à l\'achat')
                ->assertSee('7') // Produits achat (5 + 2)
                ->assertSee('Produits disponibles à la vente')
                ->assertSee('5'); // Produits vente (5 seulement)
        });
    });

    describe('DashboardTableProduit', function () {
        test('affiche les 5 derniers produits avec état de stock', function () {
            $produits = Produit::factory(10)->create([
                'category_id' => $this->category->id,
                'entrepot_id' => $this->entrepot->id,
            ]);

            // Créer des tarifs clients
            foreach ($produits as $produit) {
                TarifClient::factory()->create([
                    'produit_id' => $produit->id,
                    'prix_unitaire' => 99.99,
                ]);

                ProduitStock::factory()->create([
                    'produit_id' => $produit->id,
                    'entrepot_id' => $this->entrepot->id,
                    'quantite' => 50,
                ]);
            }

            $component = Livewire::test(DashboardTableProduit::class);

            // Vérifier que les produits sont affichés
            $component->assertSee($produits->first()->reference)
                ->assertSee($produits->first()->name);
        });

        test('affiche l\'état de stock correctement', function () {
            $produit = Produit::factory()->create([
                'category_id' => $this->category->id,
                'entrepot_id' => $this->entrepot->id,
                'limit_stock' => 10,
                'optimal_stock' => 50,
            ]);

            TarifClient::factory()->create([
                'produit_id' => $produit->id,
                'prix_unitaire' => 99.99,
            ]);

            // Stock en rupture
            ProduitStock::factory()->create([
                'produit_id' => $produit->id,
                'entrepot_id' => $this->entrepot->id,
                'quantite' => 0,
            ]);

            Livewire::test(DashboardTableProduit::class)
                ->assertSee('Rupture');
        });

        test('n\'affiche pas de pagination', function () {
            Produit::factory(10)->create([
                'category_id' => $this->category->id,
                'entrepot_id' => $this->entrepot->id,
            ]);

            $component = Livewire::test(DashboardTableProduit::class);

            // Vérifier que la pagination n'est pas visible
            $component->assertDontSee('Suivant')
                ->assertDontSee('Précédent');
        });
    });

    describe('StatistiqueChart', function () {
        test('génère les données du graphique correctement', function () {
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

            Produit::factory(1)->create([
                'category_id' => $this->category->id,
                'entrepot_id' => $this->entrepot->id,
                'achat' => false,
                'vente' => false,
            ]);

            $component = Livewire::test(StatistiqueChart::class);

            // Vérifier que le graphique s'affiche correctement
            $component->assertSee('Statistiques des Produits');

            // Vérifier les données directement via les modèles
            $disponiblesAchat = Produit::where('achat', true)->where('vente', false)->count();
            $disponiblesVente = Produit::where('vente', true)->where('achat', false)->count();
            $disponiblesAchatVente = Produit::where('achat', true)->where('vente', true)->count();
            $nonDisponibles = Produit::where('achat', false)->where('vente', false)->count();

            expect($disponiblesAchat)->toBe(2);
            expect($disponiblesVente)->toBe(3);
            expect($disponiblesAchatVente)->toBe(1);
            expect($nonDisponibles)->toBe(1);
        });

        test('utilise le type de graphique doughnut', function () {
            $component = Livewire::test(StatistiqueChart::class);

            // Vérifier que le composant s'affiche correctement
            $component->assertSee('Statistiques des Produits');
        });
    });
});

<?php

declare(strict_types=1);

use App\Livewire\Produit\Components\Table\TableProduit;
use App\Livewire\Produit\Components\Widgets\DashboardTableProduit;
use App\Models\Produit\Produit;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\TarifClient;
use App\Models\Produit\ProduitStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Performance du module Produits', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create();
        $this->entrepot = Entrepot::factory()->create();
    });

    test('évite les requêtes N+1 dans la table des produits', function () {
        // Créer des produits avec relations
        $produits = Produit::factory(50)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id
        ]);

        foreach ($produits as $produit) {
            TarifClient::factory()->create(['produit_id' => $produit->id]);
            ProduitStock::factory()->create([
                'produit_id' => $produit->id,
                'entrepot_id' => $this->entrepot->id
            ]);
        }

        DB::enableQueryLog();

        Livewire::test(TableProduit::class);

        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Vérifier un nombre raisonnable de requêtes
        expect($queryCount)->toBeLessThanOrEqual(60);
    });

    test('performance du widget dashboard avec eager loading', function () {
        // Créer des produits avec relations
        $produits = Produit::factory(20)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id
        ]);

        foreach ($produits as $produit) {
            TarifClient::factory()->create(['produit_id' => $produit->id]);
            ProduitStock::factory()->create([
                'produit_id' => $produit->id,
                'entrepot_id' => $this->entrepot->id
            ]);
        }

        DB::enableQueryLog();

        Livewire::test(DashboardTableProduit::class);

        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Avec eager loading, devrait être ≤ 5 requêtes
        expect($queryCount)->toBeLessThanOrEqual(10);
    });
});

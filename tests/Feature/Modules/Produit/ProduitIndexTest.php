<?php

declare(strict_types=1);

use App\Livewire\Produit\Produit\ProduitIndex;
use App\Models\Produit\Produit;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
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
            'entrepot_id' => $this->entrepot->id
        ]);

        $component = Livewire::test(ProduitIndex::class);

        foreach ($produits as $produit) {
            $component->assertSee($produit->reference);
        }
    });
});

<?php

declare(strict_types=1);

use App\Livewire\Produit\Components\Table\TableProduit;
use App\Models\Core\PlanComptable;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Table Produits', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create();
        $this->entrepot = Entrepot::factory()->create();
        $this->planComptable = PlanComptable::factory()->create([
            'type' => 'Revenus',
            'code' => '701',
            'account' => 'Ventes de marchandises',
        ]);
    });

    test('affiche la liste des produits', function () {
        $produits = Produit::factory(3)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        $component = Livewire::test(TableProduit::class);

        foreach ($produits as $produit) {
            $component->assertSee($produit->reference)
                ->assertSee($produit->name);
        }
    });

    test('peut filtrer les produits par référence', function () {
        $produit1 = Produit::factory()->create([
            'reference' => 'PRD-001',
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        $produit2 = Produit::factory()->create([
            'reference' => 'PRD-002',
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        Livewire::test(TableProduit::class)
            ->filterTable('reference', ['reference' => 'PRD-001'])
            ->assertSee($produit1->reference)
            ->assertDontSee($produit2->reference);
    });

    test('valide les champs requis lors de la création', function () {
        Livewire::test(TableProduit::class)
            ->callTableAction('create', data: [])
            ->assertHasTableActionErrors(['name', 'category_id', 'entrepot_id']);
    });

    test('peut supprimer des produits en masse', function () {
        $produits = Produit::factory(3)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        Livewire::test(TableProduit::class)
            ->callTableBulkAction('allDelete', $produits->pluck('id')->toArray());

        foreach ($produits as $produit) {
            $this->assertDatabaseMissing('produits', ['id' => $produit->id]);
        }
    });

    test('peut exporter les produits', function () {
        // Créer et authentifier un utilisateur
        $user = App\Models\User::factory()->create();
        $this->actingAs($user);

        Produit::factory(5)->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
        ]);

        $component = Livewire::test(TableProduit::class);

        $component->callTableBulkAction('export', []);

        // Vérifier que l'export a été déclenché
        $component->assertHasNoTableBulkActionErrors();
    });
});

<?php

declare(strict_types=1);

use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\TarifClient;
use Database\Factories\Produit\ProduitFactory;

describe('Performance des modèles Produit', function () {
    beforeEach(function () {
        // Réinitialiser les compteurs avant chaque test
        ProduitFactory::resetCounters();
    });

    it('peut créer de nombreux produits efficacement', function () {
        // Créer les relations partagées une seule fois
        $category = Category::factory()->create(['name' => 'Matériaux Performance']);
        $entrepot = Entrepot::factory()->create(['name' => 'Entrepôt Performance']);

        $startTime = microtime(true);

        // Utiliser la méthode performance() pour les optimisations
        Produit::factory()
            ->performance()
            ->count(1000)
            ->create();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect(Produit::count())->toBe(1000)
            ->and($executionTime)->toBeLessThan(5); // Objectif plus ambitieux : 2 secondes
    });

    it('peut créer des produits en lot avec insert en masse', function () {
        $category = Category::factory()->create();
        $entrepot = Entrepot::factory()->create();

        $startTime = microtime(true);

        // Préparer les données en lot
        $produitsData = [];
        for ($i = 1; $i <= 1000; $i++) {
            $produitsData[] = [
                'reference' => 'PRD-'.mb_str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'name' => 'Produit Test '.$i,
                'achat' => true,
                'vente' => true,
                'category_id' => $category->id,
                'entrepot_id' => $entrepot->id,
                'limit_stock' => 10.0,
                'optimal_stock' => 50.0,
                'poids_value' => 1.0,
                'poids_unite' => 'kg',
                'longueur' => 100.0,
                'largeur' => 50.0,
                'hauteur' => 25.0,
                'llh_unite' => 'mm',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert en masse (beaucoup plus rapide)
        Produit::insert($produitsData);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect(Produit::count())->toBe(1000)
            ->and($executionTime)->toBeLessThan(0.5); // Très rapide avec insert en masse
    });

    it('peut interroger efficacement les stocks', function () {
        $category = Category::factory()->create();
        $entrepot = Entrepot::factory()->create();

        $produits = Produit::factory()
            ->performance()
            ->count(100)
            ->create();

        // Créer les stocks en lot
        $stocksData = [];
        foreach ($produits as $produit) {
            for ($i = 0; $i < 3; $i++) {
                $stocksData[] = [
                    'produit_id' => $produit->id,
                    'entrepot_id' => $entrepot->id,
                    'quantite' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        ProduitStock::insert($stocksData);

        $startTime = microtime(true);

        $stocksEnStock = ProduitStock::enStock()->with(['produit', 'entrepot'])->get();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect($stocksEnStock->count())->toBeGreaterThan(0)
            ->and($executionTime)->toBeLessThan(1); // Moins d'1 seconde
    });

    it('peut calculer les tarifs en masse efficacement', function () {
        $category = Category::factory()->create();
        $entrepot = Entrepot::factory()->create();

        $produits = Produit::factory()
            ->performance()
            ->count(100)
            ->create();

        $tarifs = collect();
        foreach ($produits as $produit) {
            $tarifs->push(TarifClient::factory()->create([
                'produit_id' => $produit->id,
                'service_id' => null,
            ]));
        }

        $startTime = microtime(true);

        $tarifsAvecCalculs = $tarifs->map(function ($tarif) {
            return [
                'id' => $tarif->id,
                'prix_ttc' => $tarif->prix_ttc,
                'total_pour_10' => $tarif->calculerPrixTotalTTC(10),
            ];
        });

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect($tarifsAvecCalculs)->toHaveCount(100)
            ->and($executionTime)->toBeLessThan(0.5); // Moins de 0.5 seconde
    });
});

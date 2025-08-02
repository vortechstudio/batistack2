<?php

declare(strict_types=1);

use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\TarifClient;

describe('Performance des modèles Produit', function () {
    it('peut créer de nombreux produits efficacement', function () {
        $startTime = microtime(true);

        Produit::factory()->count(1000)->create();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect(Produit::count())->toBe(1000)
            ->and($executionTime)->toBeLessThan(5); // Moins de 5 secondes
    });

    it('peut interroger efficacement les stocks', function () {
        $produits = Produit::factory()->count(100)->create();

        foreach ($produits as $produit) {
            ProduitStock::factory()->count(3)->create(['produit_id' => $produit->id]);
        }

        $startTime = microtime(true);

        $stocksEnStock = ProduitStock::enStock()->with(['produit', 'entrepot'])->get();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect($stocksEnStock->count())->toBeGreaterThan(0)
            ->and($executionTime)->toBeLessThan(1); // Moins d'1 seconde
    });

    it('peut calculer les tarifs en masse efficacement', function () {
        $produits = Produit::factory()->count(100)->create();
        $tarifs = collect();

        foreach ($produits as $produit) {
            $tarifs->push(TarifClient::factory()->create(['produit_id' => $produit->id]));
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

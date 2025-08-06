<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Performance', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

// Helper pour mesurer le temps d'exécution
function measureExecutionTime(callable $callback): float
{
    $start = microtime(true);
    $callback();

    return microtime(true) - $start;
}

// Helper pour mesurer l'utilisation mémoire
function measureMemoryUsage(callable $callback): int
{
    $memoryBefore = memory_get_usage(true);
    $callback();

    return memory_get_usage(true) - $memoryBefore;
}

// Helper functions pour les tests Produit
function createProduitWithStock(int $quantite = 100): array
{
    $produit = App\Models\Produit\Produit::factory()->create();
    $entrepot = App\Models\Produit\Entrepot::factory()->create();
    $stock = App\Models\Produit\ProduitStock::factory()->create([
        'produit_id' => $produit->id,
        'entrepot_id' => $entrepot->id,
        'quantite' => $quantite,
    ]);

    return compact('produit', 'entrepot', 'stock');
}

function createTarifWithProduit(float $prix = 100.00, string $tva = '20.0'): array
{
    $produit = App\Models\Produit\Produit::factory()->create();
    $tarif = App\Models\Produit\TarifClient::factory()->create([
        'produit_id' => $produit->id,
        'prix_unitaire' => $prix,
        'taux_tva' => $tva,
    ]);

    return compact('produit', 'tarif');
}

function something()
{
    // ..
}

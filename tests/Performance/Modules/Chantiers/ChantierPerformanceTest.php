<?php

declare(strict_types=1);

use App\Models\Chantiers\Chantiers;
use App\Models\Chantiers\ChantierRessources;
use App\Models\RH\Employe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('évite les requêtes N+1 pour les ressources de chantier', function () {
    // Créer des chantiers avec ressources
    $chantiers = Chantiers::factory(5)->create();
    $employes = Employe::factory(20)->create();

    // Associer des ressources aux chantiers
    $chantiers->each(function ($chantier) use ($employes) {
        $employes->random(5)->each(function ($employe) use ($chantier) {
            ChantierRessources::factory()->create([
                'chantiers_id' => $chantier->id,
                'employe_id' => $employe->id,
            ]);
        });
    });

    DB::enableQueryLog();

    // Charger les chantiers avec leurs ressources
    $result = Chantiers::with(['ressources.employe'])
        ->get();

    $queryCount = count(DB::getQueryLog());
    DB::disableQueryLog();

    // Vérifier le nombre de requêtes (chantiers + ressources + employés)
    expect($queryCount)->toBeLessThanOrEqual(3);
});

test('performance de la recherche de chantiers', function () {
    // Créer beaucoup de chantiers
    Chantiers::factory(1000)->create();

    $startTime = microtime(true);

    // Test de recherche
    $results = Chantiers::where('nom', 'like', '%test%')
        ->orWhere('description', 'like', '%test%')
        ->limit(50)
        ->get();

    $executionTime = microtime(true) - $startTime;

    // Vérifier que la recherche est rapide
    expect($executionTime)->toBeLessThan(0.5);
});

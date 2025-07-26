<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('utilisation mémoire lors du traitement en masse', function () {
    // Créer des données
    Employe::factory(500)->create();

    $memoryBefore = memory_get_usage(true);

    // Traitement par chunks pour éviter les problèmes de mémoire
    Employe::chunk(100, function ($employes) {
        foreach ($employes as $employe) {
            // Simulation d'un traitement
            $employe->nom_complet;
        }
    });

    $memoryAfter = memory_get_usage(true);
    $memoryUsed = $memoryAfter - $memoryBefore;

    // Vérifier que l'utilisation mémoire reste raisonnable (< 50MB)
    expect($memoryUsed)->toBeLessThan(50 * 1024 * 1024);
});

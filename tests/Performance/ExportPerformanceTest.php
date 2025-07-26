<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('performance export CSV employés', function () {
    // Créer des employés
    Employe::factory(1000)->create();

    $startTime = microtime(true);

    // Simuler un export
    $data = Employe::select(['nom', 'prenom', 'email', 'poste'])
        ->get()
        ->toArray();

    $executionTime = microtime(true) - $startTime;

    // Vérifier que l'export est rapide
    expect($executionTime)->toBeLessThan(2.0);
    expect($data)->toHaveCount(1000);
});

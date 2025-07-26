<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use App\Models\RH\EmployeContrat;
use App\Models\RH\EmployeInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('évite les requêtes N+1 lors du chargement des employés avec leurs relations', function () {
    // Créer des données de test
    $employes = Employe::factory(10)
        ->has(EmployeContrat::factory())
        ->has(EmployeInfo::factory())
        ->create();

    // Compter les requêtes
    DB::enableQueryLog();

    // Charger les employés avec eager loading
    $result = Employe::with(['contrats', 'infos', 'user'])
        ->get();

    $queryCount = count(DB::getQueryLog());
    DB::disableQueryLog();

    // Vérifier qu'on n'a pas plus de 4 requêtes (employés + 3 relations)
    expect($queryCount)->toBeLessThanOrEqual(4);
    expect($result)->toHaveCount(10);
});

test('performance du calcul des salaires en masse', function () {
    // Créer 100 employés avec contrats
    Employe::factory(100)
        ->has(EmployeContrat::factory())
        ->create();

    $startTime = microtime(true);

    // Opération à tester
    $salaires = Employe::with('contrats')
        ->get()
        ->map(fn ($employe) => $employe->contrats->sum('salaire_base'));

    $executionTime = microtime(true) - $startTime;

    // Vérifier que l'opération prend moins de 1 seconde
    expect($executionTime)->toBeLessThan(1.0);
    expect($salaires)->toHaveCount(100);
});

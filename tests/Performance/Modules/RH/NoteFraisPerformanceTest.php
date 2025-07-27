<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

describe('NoteFrais Performance Tests', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    });

    test('évite les requêtes N+1 lors du chargement des notes avec employés', function () {
        // Créer des données de test
        $employes = Employe::factory()->count(10)->create();

        foreach ($employes as $employe) {
            NoteFrais::factory()->count(5)->create([
                'employe_id' => $employe->id,
            ]);
        }

        DB::enableQueryLog();

        // Charger les notes avec leurs employés
        $notes = NoteFrais::with('employe')->get();

        $queryCount = count(DB::getQueryLog());

        // Vérifier qu'on a bien 50 notes
        expect($notes)->toHaveCount(50);

        // Vérifier qu'on n'a pas plus de 3 requêtes (1 pour les notes, 1 pour les employés, 1 éventuelle pour les relations)
        expect($queryCount)->toBeLessThanOrEqual(3);

        DB::disableQueryLog();
    });

    test('évite les requêtes N+1 lors du chargement des notes avec détails', function () {
        $employe = Employe::factory()->create();

        // Créer 20 notes avec 5 détails chacune
        for ($i = 0; $i < 20; $i++) {
            $noteFrais = NoteFrais::factory()->create([
                'employe_id' => $employe->id,
            ]);

            NoteFraisDetail::factory()->count(5)->create([
                'note_frais_id' => $noteFrais->id,
            ]);
        }

        DB::enableQueryLog();

        // Charger les notes avec leurs détails
        $notes = NoteFrais::with('details')->get();

        $queryCount = count(DB::getQueryLog());

        // Vérifier qu'on a bien 20 notes
        expect($notes)->toHaveCount(20);

        // Vérifier que chaque note a 5 détails
        expect($notes->first()->details)->toHaveCount(5);

        // Vérifier qu'on n'a pas plus de 3 requêtes
        expect($queryCount)->toBeLessThanOrEqual(3);

        DB::disableQueryLog();
    });

    test('performance du calcul des montants totaux en masse', function () {
        $employes = Employe::factory()->count(5)->create();

        // Créer 100 notes avec des détails
        foreach ($employes as $employe) {
            for ($i = 0; $i < 20; $i++) {
                $noteFrais = NoteFrais::factory()->create([
                    'employe_id' => $employe->id,
                ]);

                NoteFraisDetail::factory()->count(3)->create([
                    'note_frais_id' => $noteFrais->id,
                    'montant_ht' => fake()->randomFloat(2, 10, 500),
                    'montant_tva' => fake()->randomFloat(2, 2, 100),
                    'montant_ttc' => fake()->randomFloat(2, 12, 600),
                ]);
            }
        }

        $startTime = microtime(true);

        // Calculer les montants totaux pour toutes les notes
        $notes = NoteFrais::with('details')->get();

        $totalMontantHT = 0;
        $totalMontantTVA = 0;
        $totalMontantTTC = 0;

        foreach ($notes as $note) {
            $totalMontantHT += $note->details->sum('montant_ht');
            $totalMontantTVA += $note->details->sum('montant_tva');
            $totalMontantTTC += $note->details->sum('montant_ttc');
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Vérifier que le calcul prend moins de 1 seconde
        expect($executionTime)->toBeLessThan(1.0);

        // Vérifier qu'on a bien des montants calculés
        expect($totalMontantHT)->toBeGreaterThan(0)
            ->and($totalMontantTVA)->toBeGreaterThan(0)
            ->and($totalMontantTTC)->toBeGreaterThan(0);
    });

    test('performance de la recherche avec filtres multiples', function () {
        $employes = Employe::factory()->count(10)->create();

        // Créer 200 notes avec différents statuts
        foreach ($employes as $employe) {
            NoteFrais::factory()->count(5)->brouillon()->create(['employe_id' => $employe->id]);
            NoteFrais::factory()->count(5)->soumise()->create(['employe_id' => $employe->id]);
            NoteFrais::factory()->count(5)->validee()->create(['employe_id' => $employe->id]);
            NoteFrais::factory()->count(5)->payee()->create(['employe_id' => $employe->id]);
        }

        $startTime = microtime(true);

        // Recherche complexe avec plusieurs filtres
        $results = NoteFrais::with('employe')
            ->where('statut', 'validee')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->whereHas('employe', function ($query) {
                $query->where('nom', 'like', '%test%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Vérifier que la recherche prend moins de 0.5 seconde
        expect($executionTime)->toBeLessThan(0.5);
    });

    test('performance de la génération de numéros uniques en masse', function () {
        $employe = Employe::factory()->create();

        $startTime = microtime(true);

        // Créer 100 notes de frais rapidement
        $notes = [];
        for ($i = 0; $i < 100; $i++) {
            $notes[] = NoteFrais::factory()->create([
                'employe_id' => $employe->id,
            ]);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Vérifier que la création prend moins de 2 secondes
        expect($executionTime)->toBeLessThan(2.0);

        // Vérifier que tous les numéros sont uniques
        $numeros = collect($notes)->pluck('numero')->unique();
        expect($numeros)->toHaveCount(100);
    });

    test('performance de l\'export de données volumineuses', function () {
        $employes = Employe::factory()->count(5)->create();

        // Créer 500 notes avec détails
        foreach ($employes as $employe) {
            for ($i = 0; $i < 100; $i++) {
                $noteFrais = NoteFrais::factory()->create([
                    'employe_id' => $employe->id,
                ]);

                NoteFraisDetail::factory()->count(2)->create([
                    'note_frais_id' => $noteFrais->id,
                ]);
            }
        }

        $startTime = microtime(true);

        // Simuler un export de données
        $exportData = NoteFrais::with(['employe', 'details'])
            ->get()
            ->map(function ($note) {
                return [
                    'numero' => $note->numero,
                    'employe' => $note->employe->nom_complet,
                    'montant_total' => $note->details->sum('montant_ttc'),
                    'nb_details' => $note->details->count(),
                    'status' => $note->statut?->value ?? 'brouillon',
                ];
            });

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Vérifier que l'export prend moins de 3 secondes
        expect($executionTime)->toBeLessThan(3.0);

        // Vérifier qu'on a bien toutes les données
        expect($exportData)->toHaveCount(500);
    });

    test('utilisation mémoire lors du traitement de gros volumes', function () {
        $employes = Employe::factory()->count(3)->create();

        // Créer 300 notes avec détails
        foreach ($employes as $employe) {
            for ($i = 0; $i < 100; $i++) {
                $noteFrais = NoteFrais::factory()->create([
                    'employe_id' => $employe->id,
                ]);

                NoteFraisDetail::factory()->count(5)->create([
                    'note_frais_id' => $noteFrais->id,
                ]);
            }
        }

        $memoryBefore = memory_get_usage(true);

        // Traitement par chunks pour optimiser la mémoire
        $totalProcessed = 0;
        NoteFrais::with(['employe', 'details'])
            ->chunk(50, function ($notes) use (&$totalProcessed) {
                foreach ($notes as $note) {
                    // Simuler un traitement
                    $montantTotal = $note->details->sum('montant_ttc');
                    $totalProcessed++;
                }
            });

        $memoryAfter = memory_get_usage(true);
        $memoryUsed = $memoryAfter - $memoryBefore;

        // Vérifier qu'on a traité toutes les notes
        expect($totalProcessed)->toBe(300);

        // Vérifier que l'utilisation mémoire reste raisonnable (moins de 50MB)
        expect($memoryUsed)->toBeLessThan(50 * 1024 * 1024);
    });
});

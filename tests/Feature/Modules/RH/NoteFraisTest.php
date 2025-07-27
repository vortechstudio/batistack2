<?php

declare(strict_types=1);

use App\Enums\RH\StatusNoteFrais;
use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('NoteFrais Model', function () {
    beforeEach(function () {
        $this->employe = Employe::factory()->create();
        $this->validateur = User::factory()->create();
    });

    test('peut être créé avec les attributs requis', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
            'date_debut' => '2024-01-01',
            'date_fin' => '2024-01-31',
            'statut' => StatusNoteFrais::BROUILLON,
        ]);

        expect($noteFrais)->toBeInstanceOf(NoteFrais::class)
            ->and($noteFrais->employe_id)->toBe($this->employe->id)
            ->and($noteFrais->statut)->toBe(StatusNoteFrais::BROUILLON);
    });

    test('génère automatiquement un numéro unique', function () {
        $noteFrais1 = NoteFrais::factory()->create(['employe_id' => $this->employe->id]);
        $noteFrais2 = NoteFrais::factory()->create(['employe_id' => $this->employe->id]);

        expect($noteFrais1->numero)->not()->toBeNull()
            ->and($noteFrais2->numero)->not()->toBeNull()
            ->and($noteFrais1->numero)->not()->toBe($noteFrais2->numero);
    });

    test('a un statut brouillon par défaut', function () {
        $noteFrais = NoteFrais::factory()->brouillon()->create([
            'employe_id' => $this->employe->id,
        ]);

        expect($noteFrais->statut)->toBe(StatusNoteFrais::BROUILLON);
    });

    test('appartient à un employé', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        expect($noteFrais->employe)->toBeInstanceOf(Employe::class)
            ->and($noteFrais->employe->id)->toBe($this->employe->id);
    });

    test('peut avoir un validateur', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
            'validateur_id' => $this->validateur->id,
        ]);

        expect($noteFrais->validateur)->toBeInstanceOf(User::class)
            ->and($noteFrais->validateur->id)->toBe($this->validateur->id);
    });

    test('peut avoir plusieurs détails', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        NoteFraisDetail::factory()->count(3)->create([
            'note_frais_id' => $noteFrais->id,
        ]);

        expect($noteFrais->details)->toHaveCount(3);
    });

    test('calcule le montant total calculé', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        NoteFraisDetail::factory()->create([
            'note_frais_id' => $noteFrais->id,
            'montant_ht' => 83.33,
            'taux_tva' => 20,
            'montant_tva' => 16.67,
            'montant_ttc' => 100.00,
        ]);

        NoteFraisDetail::factory()->create([
            'note_frais_id' => $noteFrais->id,
            'montant_ht' => 41.67,
            'taux_tva' => 20,
            'montant_tva' => 8.33,
            'montant_ttc' => 50.00,
        ]);

        $noteFrais->refresh();

        expect($noteFrais->montant_total_calcule)->toBe(150.00);
    });

    test('peut être soumis', function () {
        $noteFrais = NoteFrais::factory()->brouillon()->create([
            'employe_id' => $this->employe->id,
        ]);

        $noteFrais->soumettre();

        expect($noteFrais->statut)->toBe(StatusNoteFrais::SOUMISE)
            ->and($noteFrais->date_soumission)->not()->toBeNull();
    });

    test('peut être validé', function () {
        $noteFrais = NoteFrais::factory()->soumise()->create([
            'employe_id' => $this->employe->id,
        ]);

        $noteFrais->valider($this->validateur->id);

        expect($noteFrais->statut)->toBe(StatusNoteFrais::VALIDEE)
            ->and($noteFrais->validateur_id)->toBe($this->validateur->id)
            ->and($noteFrais->date_validation)->not()->toBeNull();
    });

    test('peut être refusé', function () {
        $noteFrais = NoteFrais::factory()->soumise()->create([
            'employe_id' => $this->employe->id,
            'motif_refus' => null, // S'assurer qu'aucun motif n'est défini initialement
        ]);

        $motif = 'Justificatifs manquants';
        $noteFrais->refuser($this->validateur->id, $motif);

        expect($noteFrais->statut)->toBe(StatusNoteFrais::REFUSEE)
            ->and($noteFrais->validateur_id)->toBe($this->validateur->id)
            ->and($noteFrais->motif_refus)->toBe($motif);
    });

    test('filtre par statut', function () {
        NoteFrais::factory()->brouillon()->create(['employe_id' => $this->employe->id]);
        NoteFrais::factory()->soumise()->create(['employe_id' => $this->employe->id]);
        NoteFrais::factory()->validee()->create(['employe_id' => $this->employe->id]);

        $brouillons = NoteFrais::where('statut', StatusNoteFrais::BROUILLON->value)->get();
        $soumises = NoteFrais::where('statut', StatusNoteFrais::SOUMISE->value)->get();
        $validees = NoteFrais::where('statut', StatusNoteFrais::VALIDEE->value)->get();

        expect($brouillons)->toHaveCount(1)
            ->and($soumises)->toHaveCount(1)
            ->and($validees)->toHaveCount(1);
    });

    test('filtre par employé', function () {
        $autreEmploye = Employe::factory()->create();

        NoteFrais::factory()->create(['employe_id' => $this->employe->id]);
        NoteFrais::factory()->create(['employe_id' => $autreEmploye->id]);

        $notesEmploye = NoteFrais::where('employe_id', $this->employe->id)->get();
        $notesAutreEmploye = NoteFrais::where('employe_id', $autreEmploye->id)->get();

        expect($notesEmploye)->toHaveCount(1)
            ->and($notesAutreEmploye)->toHaveCount(1);
    });

    test('filtre par période', function () {
        $noteFrais1 = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
            'date_debut' => '2024-01-01',
            'date_fin' => '2024-01-31',
        ]);

        $noteFrais2 = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
            'date_debut' => '2024-02-01',
            'date_fin' => '2024-02-29',
        ]);

        $notesJanvier = NoteFrais::whereBetween('date_debut', ['2024-01-01', '2024-01-31'])->get();
        $notesFevrier = NoteFrais::whereBetween('date_debut', ['2024-02-01', '2024-02-29'])->get();

        expect($notesJanvier)->toHaveCount(1)
            ->and($notesFevrier)->toHaveCount(1);
    });

    test('utilise l\'accesseur numero_complet', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
            'numero' => 'NF-2024-001',
        ]);

        expect($noteFrais->numero_complet)->toContain('NF-2024-001');
    });

    test('compte les justificatifs', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        NoteFraisDetail::factory()->avecJustificatif()->create([
            'note_frais_id' => $noteFrais->id,
        ]);

        NoteFraisDetail::factory()->sansJustificatif()->create([
            'note_frais_id' => $noteFrais->id,
        ]);

        expect($noteFrais->nombre_justificatifs)->toBe(1);
    });

    test('détermine si validable', function () {
        $noteFrais = NoteFrais::factory()->soumise()->create([
            'employe_id' => $this->employe->id,
        ]);

        NoteFraisDetail::factory()->avecJustificatif()->create([
            'note_frais_id' => $noteFrais->id,
        ]);

        expect($noteFrais->est_validable)->toBeTrue();
    });

    test('utilise le scope en attente', function () {
        NoteFrais::factory()->brouillon()->create(['employe_id' => $this->employe->id]);
        NoteFrais::factory()->soumise()->create(['employe_id' => $this->employe->id]);
        NoteFrais::factory()->validee()->create(['employe_id' => $this->employe->id]);

        $notesEnAttente = NoteFrais::enAttente()->get();

        expect($notesEnAttente)->toHaveCount(1);
    });

    test('peut être payé', function () {
        $noteFrais = NoteFrais::factory()->validee()->create([
            'employe_id' => $this->employe->id,
        ]);

        $noteFrais->payer();

        expect($noteFrais->statut)->toBe(StatusNoteFrais::PAYEE)
            ->and($noteFrais->date_paiement)->not()->toBeNull();
    });

    test('calcule les montants lors de la sauvegarde', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        NoteFraisDetail::factory()->create([
            'note_frais_id' => $noteFrais->id,
            'montant_ht' => 83.33,
            'taux_tva' => 20,
            'montant_tva' => 16.67,
            'montant_ttc' => 100.00,
        ]);

        NoteFraisDetail::factory()->create([
            'note_frais_id' => $noteFrais->id,
            'montant_ht' => 62.92,
            'taux_tva' => 20,
            'montant_tva' => 12.58,
            'montant_ttc' => 75.50,
        ]);

        $noteFrais->calculerMontants();

        expect($noteFrais->fresh()->montant_total)->toBe(175.5);
    });

    test('utilise le soft delete', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        $noteFrais->delete();

        expect(NoteFrais::count())->toBe(0)
            ->and(NoteFrais::withTrashed()->count())->toBe(1);
    });

    test('peut être restauré après suppression', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);

        $noteFrais->delete();
        $noteFrais->restore();

        expect(NoteFrais::count())->toBe(1)
            ->and(NoteFrais::withTrashed()->count())->toBe(1);
    });
});

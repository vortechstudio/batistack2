<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('NoteFraisDetail Model', function () {
    beforeEach(function () {
        $this->employe = Employe::factory()->create();
        $this->noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);
    });

    test('peut créer un détail de note de frais', function () {
        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        expect($detail)->toBeInstanceOf(NoteFraisDetail::class)
            ->and($detail->note_frais_id)->toBe($this->noteFrais->id);

        $this->assertDatabaseHas('note_frais_details', [
            'id' => $detail->id,
            'note_frais_id' => $this->noteFrais->id,
        ]);
    });

    test('appartient à une note de frais', function () {
        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        expect($detail->noteFrais)->toBeInstanceOf(NoteFrais::class)
            ->and($detail->noteFrais->id)->toBe($this->noteFrais->id);
    });

    test('peut avoir différents types de frais', function () {
        $transport = NoteFraisDetail::factory()->transport()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $restauration = NoteFraisDetail::factory()->restauration()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $hebergement = NoteFraisDetail::factory()->hebergement()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        expect($transport->type)->toBe('transport')
            ->and($restauration->type)->toBe('restauration')
            ->and($hebergement->type)->toBe('hebergement');
    });

    test('calcule automatiquement le montant TTC', function () {
        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
            'montant_ht' => 100.00,
            'taux_tva' => 20.00,
            'montant_tva' => null,
            'montant_ttc' => null,
        ]);

        // Recharger le modèle pour voir les valeurs calculées
        $detail->refresh();

        expect($detail->montant_ht)->toBe('100.00')
            ->and($detail->taux_tva)->toBe('20.00')
            ->and($detail->montant_tva)->toBe('20.00')
            ->and($detail->montant_ttc)->toBe('120.00');
    });

    test('peut avoir un justificatif', function () {
        $detail = NoteFraisDetail::factory()->avecJustificatif()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        expect($detail->justificatif_path)->not->toBeNull()
            ->and($detail->a_justificatif)->toBeTrue();
    });

    test('peut être sans justificatif', function () {
        $detail = NoteFraisDetail::factory()->sansJustificatif()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        expect($detail->justificatif_path)->toBeNull()
            ->and($detail->a_justificatif)->toBeFalse();
    });

    test('peut être remboursable ou non', function () {
        $remboursable = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
            'remboursable' => true,
        ]);

        $nonRemboursable = NoteFraisDetail::factory()->nonRemboursable()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        expect($remboursable->remboursable)->toBeTrue()
            ->and($nonRemboursable->remboursable)->toBeFalse();
    });

    test('génère un libellé automatique selon le type', function () {
        $transport = NoteFraisDetail::factory()->transport()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $restauration = NoteFraisDetail::factory()->restauration()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $transportLibelles = [
            'Déplacement client', 'Transport chantier', 'Mission commerciale',
            'Formation externe', 'Rendez-vous fournisseur', 'Visite technique',
        ];

        $restaurationLibelles = [
            'Repas client', 'Déjeuner mission', 'Dîner d\'affaires',
            'Petit-déjeuner réunion', 'Pause café équipe',
        ];

        expect(in_array($transport->libelle, $transportLibelles))->toBeTrue()
            ->and(in_array($restauration->libelle, $restaurationLibelles))->toBeTrue();
    });

    test('peut avoir des informations de kilométrage pour le transport', function () {
        $transport = NoteFraisDetail::factory()->transport()->create([
            'note_frais_id' => $this->noteFrais->id,
            'kilometrage' => 150,
            'lieu_depart' => 'Paris',
            'lieu_arrivee' => 'Lyon',
        ]);

        expect($transport->kilometrage)->toBe('150.00')
            ->and($transport->lieu_depart)->toBe('Paris')
            ->and($transport->lieu_arrivee)->toBe('Lyon');
    });

    test('peut être associé à un chantier', function () {
        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
            'chantier_id' => 1,
        ]);

        expect($detail->chantier_id)->toBe(1);
    });

    test('peut avoir des métadonnées JSON', function () {
        $metadata = ['custom_field' => 'custom_value', 'autre_info' => 123];

        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
            'metadata' => $metadata,
        ]);

        expect($detail->metadata)->toBe($metadata)
            ->and($detail->metadata['custom_field'])->toBe('custom_value');
    });

    test('filtre par type de frais', function () {
        NoteFraisDetail::factory()->transport()->create(['note_frais_id' => $this->noteFrais->id]);
        NoteFraisDetail::factory()->restauration()->create(['note_frais_id' => $this->noteFrais->id]);
        NoteFraisDetail::factory()->hebergement()->create(['note_frais_id' => $this->noteFrais->id]);

        $transports = NoteFraisDetail::where('type_frais', 'transport')->get();
        $restaurations = NoteFraisDetail::where('type_frais', 'restauration')->get();
        $hebergements = NoteFraisDetail::where('type_frais', 'hebergement')->get();

        expect($transports)->toHaveCount(1)
            ->and($restaurations)->toHaveCount(1)
            ->and($hebergements)->toHaveCount(1);
    });

    test('filtre par remboursabilité', function () {
        NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
            'remboursable' => true,
        ]);

        NoteFraisDetail::factory()->nonRemboursable()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $remboursables = NoteFraisDetail::where('remboursable', true)->get();
        $nonRemboursables = NoteFraisDetail::where('remboursable', false)->get();

        expect($remboursables)->toHaveCount(1)
            ->and($nonRemboursables)->toHaveCount(1);
    });

    test('utilise le soft delete', function () {
        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $detail->delete();

        $this->assertSoftDeleted('note_frais_details', ['id' => $detail->id]);
        expect(NoteFraisDetail::all())->toHaveCount(0)
            ->and(NoteFraisDetail::withTrashed()->get())->toHaveCount(1);
    });

    test('peut restaurer un détail supprimé', function () {
        $detail = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);

        $detail->delete();
        $detail->restore();

        expect(NoteFraisDetail::all())->toHaveCount(1)
            ->and($detail->fresh())->not->toBeNull();
    });
});

<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use App\Models\RH\NoteFraisPaiement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('NoteFraisPaiement Model', function () {
    beforeEach(function () {
        $this->employe = Employe::factory()->create();
        $this->noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
        ]);
        $this->noteFraisDetails = NoteFraisDetail::factory()->create([
            'note_frais_id' => $this->noteFrais->id,
        ]);
    });

    test('peut créer un paiement', function () {
        // Créer un mode de règlement pour le test
        $modeReglement = \App\Models\Core\ModeReglement::create([
            'code' => 'VIR',
            'name' => 'Virement bancaire',
            'type_paiement' => json_encode(['client', 'fournisseur']),
            'bridgeable' => true,
        ]);

        // Créer un paiement pour la note de frais
        $paiement = \App\Models\RH\NoteFraisPaiement::create([
            'note_frais_id' => $this->noteFrais->id,
            'mode_reglement_id' => $modeReglement->id,
            'montant' => 150.75,
            'date_paiement' => now(),
        ]);

        // Vérifications
        expect($paiement)->toBeInstanceOf(\App\Models\RH\NoteFraisPaiement::class)
            ->and($paiement->note_frais_id)->toBe($this->noteFrais->id)
            ->and($paiement->mode_reglement_id)->toBe($modeReglement->id)
            ->and($paiement->montant)->toBe(150.75)
            ->and($paiement->numero_paiement)->toStartWith('PNF-'.date('Y').'-') // Vérifie le format auto-généré
            ->and($paiement->noteFrais)->toBeInstanceOf(\App\Models\RH\NoteFrais::class)
            ->and($paiement->modeReglement)->toBeInstanceOf(\App\Models\Core\ModeReglement::class)
            ->and($paiement->typePaiement)->toBe('Virement bancaire'); // Accessor
    });
});

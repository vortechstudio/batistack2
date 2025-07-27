<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
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

    test('peut créer un paiement', function () {});
});

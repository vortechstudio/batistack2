<?php

declare(strict_types=1);

use App\Livewire\Humans\Frais\Frais;
use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Frais Livewire Component', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->employe = Employe::factory()->create();
        $this->actingAs($this->user);
    });

    test('peut afficher le composant', function () {
        Livewire::test(Frais::class)
            ->assertStatus(200)
            ->assertSee('Liste des notes de frais');
    });

    test('affiche les notes de frais existantes', function () {
        $noteFrais = NoteFrais::factory()->create([
            'employe_id' => $this->employe->id,
            'numero' => 'NF-2024-0001',
        ]);

        Livewire::test(Frais::class)
            ->assertSee('NF-2024-0001')
            ->assertSee($this->employe->nom_complet);
    });

    test('peut créer une nouvelle note de frais', function () {
        Livewire::test(Frais::class)
            ->callTableAction('create', data: [
                'employe_id' => $this->employe->id,
                'date_debut' => '2024-01-01',
                'date_fin' => '2024-01-07',
            ])
            ->assertHasNoTableActionErrors();

        expect(NoteFrais::count())->toBe(1);
    });

    test('affiche les avatars des employés', function () {
        $employe = Employe::factory()->create();

        NoteFrais::factory()->create([
            'employe_id' => $employe->id,
        ]);

        Livewire::test(Frais::class)
            ->assertSee($employe->full_name);
    });

    test('affiche un message quand aucune note de frais n\'existe', function () {
        Livewire::test(Frais::class)
            ->assertSee('Aucune note de frais trouvée');
    });
});

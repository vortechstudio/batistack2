<?php

declare(strict_types=1);

use App\Enums\RH\TypeContrat;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

test("Accès à l'interface salariés pour nouveau salarié", function() {
    $this->actingAs($user = User::factory()->create());

    $this->get('/humans/salaries')->assertOk();
});

test("Création automatique d'un salariés", function() {
    $this->actingAs($user = User::factory()->create());
    Artisan::call('install:country');

    Livewire::test(\App\Livewire\Humans\Components\Tables\TableSalaries::class)
        ->callTableAction('create', data: [
            'civility' => 'Madame',
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'email' => 'sophie.martin@example.com',
            'poste' => 'Chef de projet',
            'date_debut' => now()->format('Y-m-d H:i:s'),
            'salaire_horaire' => 20,
            'heure_travail' => 35,
            'address' => '123 Rue de la Paix',
            'code_postal' => '75000',
            'ville' => 'Paris',
            'telephone' => '0600000000',
            'type' => TypeContrat::CDD,
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'email' => 'sophie.martin@example.com',
        'role' => \App\Enums\Core\UserRole::SALARIE
    ]);

    $employe = \App\Models\RH\Employe::latest()->first();
    $this->assertDatabaseHas('employe_contrats', [
        'employe_id' => $employe->id,
        'type' => 'cdd'
    ]);
});

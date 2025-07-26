<?php

declare(strict_types=1);

use App\Models\User;

test('Accès à l\'index de la page', function () {
    $this->actingAs($user = User::factory()->create());
    $this->get('/tiers')->assertOk();
});

test('Accès la section fournisseur', function () {
    $this->actingAs($user = User::factory()->create());
    $this->get('/tiers/supply')->assertOk();
});

test('Accès la section client', function () {
    $this->actingAs($user = User::factory()->create());
    $this->get('/tiers/customers')->assertOk();
});

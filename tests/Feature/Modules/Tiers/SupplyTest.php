<?php

declare(strict_types=1);

use App\Livewire\Tiers\CreateForm;
use App\Livewire\Tiers\Supply\ListSupply;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;

test("Accès à la création d'un fournisseur", function() {
    $this->actingAs($user = User::factory()->create());
    Artisan::call('install:country');

    Livewire::test(ListSupply::class)
        ->callTableAction('create');

    $this->get('/tiers/supply/create', ['type' => 'supply'])->assertOk();
});

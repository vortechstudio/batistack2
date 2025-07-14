<?php

use App\Console\Commands\InstallBankCommand;
use App\Models\Core\Bank;
use App\Services\Powens;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;

test('installe les banques lorsque la table est vide', function () {
    // Mock Powens
    $mockBanks = ['connectors' => [
        ['uuid' => '1', 'name' => 'Banque Test 1', 'stability' => ['status' => 'stable']],
        ['uuid' => '2', 'name' => 'Banque Test 2', 'stability' => ['status' => 'beta']]
    ]];

    $powensMock = mock(Powens::class);
    $powensMock->shouldReceive('get')->andReturn($mockBanks);

    // Exécution de la commande
    Artisan::call('install:bank');

    // Vérifications
    expect(Bank::count())->toBe(40);
});

test('Ne fais rien si les banques sont déjà installer en base', function() {
    Artisan::call('install:bank');
    expect(Bank::count())->toBe(40);

    Artisan::call('install:bank');
    expect(Bank::count())->toBe(40);
});

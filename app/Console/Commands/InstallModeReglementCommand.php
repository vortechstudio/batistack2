<?php

namespace App\Console\Commands;

use App\Models\Core\ModeReglement;
use Illuminate\Console\Command;

class InstallModeReglementCommand extends Command
{
    protected $signature = 'install:mode-reglement';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (ModeReglement::count() === 0) {
            ModeReglement::create([
                'code' => 'CB',
                'name' => 'Carte Bancaire',
                'type_paiement' => json_encode(['client', 'fournisseur']),
                'bridgeable' => true,
            ]);
            ModeReglement::create([
                'code' => 'ESP',
                'name' => 'Espèce',
                'type_paiement' => json_encode(['client', 'fournisseur']),
                'bridgeable' => false,
            ]);
            ModeReglement::create([
                'code' => 'VIRSEPA',
                'name' => 'Virement SEPA',
                'type_paiement' => json_encode(['client', 'fournisseur']),
                'bridgeable' => true,
            ]);
            ModeReglement::create([
                'code' => 'PRLV',
                'name' => 'Prélèvement Bancaire',
                'type_paiement' => json_encode(['fournisseur']),
                'bridgeable' => false,
            ]);
        }
    }
}

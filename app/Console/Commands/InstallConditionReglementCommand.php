<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Core\ConditionReglement;
use Illuminate\Console\Command;

final class InstallConditionReglementCommand extends Command
{
    protected $signature = 'install:condition-reglement';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (ConditionReglement::count() === 0) {
            ConditionReglement::create([
                'code' => 'RECEP',
                'name' => 'A Réception',
                'name_document' => 'A Réception',
                'nb_jours' => 1,
                'fdm' => false,
            ]);
            ConditionReglement::create([
                'code' => '30D',
                'name' => '30 Jours',
                'name_document' => 'Réglement à 30 jours',
                'nb_jours' => 30,
                'fdm' => false,
            ]);
            ConditionReglement::create([
                'code' => '30DMONTH',
                'name' => '30 Jours fin de mois',
                'name_document' => 'Réglement à 30 jours fin de mois',
                'nb_jours' => 30,
                'fdm' => true,
            ]);
        }
    }
}

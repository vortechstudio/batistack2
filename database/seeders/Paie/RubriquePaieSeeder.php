<?php

namespace Database\Seeders\Paie;

use App\Models\RH\Paie\RubriquePaie;
use Illuminate\Database\Seeder;

class RubriquePaieSeeder extends Seeder
{
    public function run(): void
    {
        RubriquePaie::insert([
            ['nom' => 'Salaire de base', 'imposable' => true, 'soumis_cotisation' => true],
            ['nom' => 'Heures supplémentaires', 'imposable' => true, 'soumis_cotisation' => true],
            ['nom' => 'Primes', 'imposable' => true, 'soumis_cotisation' => true],
            ['nom' => 'Indemnités de congés payés', 'imposable' => true, 'soumis_cotisation' => true],
            ['nom' => 'Avantages en nature', 'imposable' => true, 'soumis_cotisation' => true],
            ['nom' => 'Net imposable', 'imposable' => false, 'soumis_cotisation' => false],
            ['nom' => 'Net social', 'imposable' => false, 'soumis_cotisation' => false],
        ]);
    }
}

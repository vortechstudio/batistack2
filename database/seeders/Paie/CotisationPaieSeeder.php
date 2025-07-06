<?php

namespace Database\Seeders\Paie;

use App\Models\RH\Paie\CotisationPaie;
use Illuminate\Database\Seeder;

class CotisationPaieSeeder extends Seeder
{
    public function run(): void
    {
        CotisationPaie::insert([
            ['nom' => 'Sécurité sociale - Maladie', 'taux_salarial' => 0.75, 'taux_patronal' => 13.00],
            ['nom' => 'Assurance vieillesse plafonnée', 'taux_salarial' => 6.90, 'taux_patronal' => 8.55],
            ['nom' => 'Assurance vieillesse déplafonnée', 'taux_salarial' => 0.40, 'taux_patronal' => 1.90],
            ['nom' => 'Allocations familiales', 'taux_salarial' => 0, 'taux_patronal' => 3.45],
            ['nom' => 'Assurance chômage', 'taux_salarial' => 0, 'taux_patronal' => 4.05],
            ['nom' => 'Retraite complémentaire AGIRC-ARRCO', 'taux_salarial' => 3.15, 'taux_patronal' => 4.72],
            ['nom' => 'CSG déductible', 'taux_salarial' => 6.80, 'taux_patronal' => 0],
            ['nom' => 'CSG/CRDS non déductible', 'taux_salarial' => 2.90, 'taux_patronal' => 0],
            ['nom' => 'Accidents du travail', 'taux_salarial' => 0, 'taux_patronal' => 0.90],
        ]);
    }
}

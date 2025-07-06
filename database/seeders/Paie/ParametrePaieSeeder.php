<?php

namespace Database\Seeders\Paie;

use App\Models\RH\Paie\ParametrePaie;
use Illuminate\Database\Seeder;

class ParametrePaieSeeder extends Seeder
{
    public function run(): void
    {
        ParametrePaie::create([
            'cle' => 'smic_horaire',
            'valeur' => 11.88,
            'description' => 'SMIC horaire brut 2025 (€)',
        ]);
        ParametrePaie::create([
            'cle' => 'plafond_securite_sociale_mensuel',
            'valeur' => 3925,
            'description' => 'Plafond mensuel Sécurité Sociale 2025 (€)',
        ]);
        ParametrePaie::create([
            'cle' => 'csg_taux',
            'valeur' => 9.2,
            'description' => 'CSG déductible (%)',
        ]);
        ParametrePaie::create([
            'cle' => 'crds_taux',
            'valeur' => 0.5,
            'description' => 'CRDS (%)',
        ]);
    }
}

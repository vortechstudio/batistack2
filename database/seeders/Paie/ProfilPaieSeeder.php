<?php

namespace Database\Seeders\Paie;

use App\Models\RH\Paie\ProfilPaie;
use Illuminate\Database\Seeder;

class ProfilPaieSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPaie::insert([
            ['nom' => 'Ouvrier'],
            ['nom' => 'Employé'],
            ['nom' => 'Agent de maîtrise'],
            ['nom' => 'Cadre'],
        ]);
    }
}

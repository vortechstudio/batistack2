<?php

declare(strict_types=1);

namespace Database\Seeders\Paie;

use App\Models\RH\Paie\ProfilPaie;
use Illuminate\Database\Seeder;

final class ProfilPaieSeeder extends Seeder
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

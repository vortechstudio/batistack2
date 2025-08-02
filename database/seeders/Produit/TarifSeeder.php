<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use Illuminate\Database\Seeder;

final class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Démarrage du seeding des tarifs...');

        // Seeder les tarifs fournisseurs
        $this->call(TarifFournisseurSeeder::class);

        // Seeder les tarifs clients
        $this->call(TarifClientSeeder::class);

        $this->command->info('✅ Seeding des tarifs terminé avec succès !');
    }
}

<?php

namespace Database\Seeders\Produit;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Démarrage du seeding Produits et Services...');

        // Seeder les catégories en premier
        $this->call(CategorySeeder::class);

        // Puis les entrepôts
        $this->call(EntrepotSeeder::class);

        // Ensuite les produits
        $this->call(ProduitSeeder::class);

        // Enfin les services
        $this->call(ServiceSeeder::class);

        $this->command->info('✅ Seeding Produits et Services terminé avec succès !');
    }
}

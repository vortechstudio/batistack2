<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use Database\Seeders\Produit\CategoryProduitSeeder;
use Database\Seeders\Produit\ProduitSeeder;
use Database\Seeders\Produit\TarifClientSeeder;
use Database\Seeders\Produit\TarifFournisseurSeeder;
use Database\Seeders\Produit\UniteMesureSeeder;
use Illuminate\Database\Seeder;

final class ProduitModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏗️  Démarrage du seeding du module Produit/Service...');

        // Ordre d'exécution important pour respecter les dépendances
        $this->call([
            CategoryProduitSeeder::class,
            UniteMesureSeeder::class,
            ProduitSeeder::class,
            TarifFournisseurSeeder::class,
            TarifClientSeeder::class,
        ]);

        $this->command->info('✅ Module Produit/Service seedé avec succès !');
        $this->afficherStatistiques();
    }

    /**
     * Afficher les statistiques de création
     */
    private function afficherStatistiques(): void
    {
        $stats = [
            'Catégories de produits' => \App\Models\Produit\CategoryProduit::count(),
            'Unités de mesure' => \App\Models\Produit\UniteMesure::count(),
            'Produits' => \App\Models\Produit\Produit::count(),
            'Tarifs fournisseurs' => \App\Models\Produit\TarifFournisseur::count(),
            'Tarifs clients' => \App\Models\Produit\TarifClient::count(),
        ];

        $this->command->info('📊 Statistiques de création :');
        foreach ($stats as $type => $count) {
            $this->command->info("   • {$type}: {$count}");
        }
    }
}
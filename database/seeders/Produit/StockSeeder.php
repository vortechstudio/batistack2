<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use Illuminate\Database\Seeder;

final class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏭 === SEEDING DES STOCKS ET MOUVEMENTS ===');

        // 1. Créer les stocks de produits
        $this->call(ProduitStockSeeder::class);

        // 2. Créer les mouvements de stock
        $this->call(ProduitStockMvmSeeder::class);

        $this->command->info('🎉 === SEEDING DES STOCKS TERMINÉ ===');
        $this->command->info('');
        $this->command->info('📊 Résumé :');
        $this->command->info('• Stocks de produits créés avec répartition réaliste');
        $this->command->info('• Mouvements de stock générés avec historique');
        $this->command->info('• Types de mouvements : entrées et sorties');
        $this->command->info('• Données temporelles : récentes et anciennes');
        $this->command->info('');
        $this->command->info('🚀 Vous pouvez maintenant utiliser les stocks dans votre application !');
    }
}

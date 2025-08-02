<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\ProduitStock;
use App\Models\Produit\ProduitStockMvm;
use Illuminate\Database\Seeder;

final class ProduitStockMvmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📊 Création des mouvements de stock...');

        // Récupérer tous les stocks
        $stocks = ProduitStock::all();

        if ($stocks->isEmpty()) {
            $this->command->warn('Aucun stock trouvé. Veuillez d\'abord exécuter ProduitStockSeeder.');

            return;
        }

        $totalMouvements = 0;
        $entrees = 0;
        $sorties = 0;

        // Créer des mouvements pour chaque stock (RÉDUIT)
        foreach ($stocks as $stock) {
            // Nombre de mouvements par stock réduit (entre 2 et 5 au lieu de 3-15)
            $nombreMouvements = rand(2, 5);

            for ($i = 0; $i < $nombreMouvements; $i++) {
                // 60% d'entrées, 40% de sorties
                $typeMovement = rand(1, 100) <= 60 ? 'entree' : 'sortie';

                $mouvement = match ($typeMovement) {
                    'entree' => ProduitStockMvm::factory()
                        ->entree()
                        ->pourStock($stock)
                        ->create(),
                    'sortie' => ProduitStockMvm::factory()
                        ->sortie()
                        ->pourStock($stock)
                        ->create(),
                };

                $totalMouvements++;

                if ($typeMovement === 'entree') {
                    $entrees++;
                } else {
                    $sorties++;
                }

                if ($totalMouvements % 50 === 0) {
                    $this->command->info("✅ {$totalMouvements} mouvements créés...");
                }
            }
        }

        // Créer des mouvements spécifiques (réduit)
        $this->creerMouvementsSpecifiques();

        // Créer des mouvements récents et anciens (réduit)
        $this->creerMouvementsTemporels();

        // Statistiques finales
        $totalFinal = ProduitStockMvm::count();
        $entreesFinales = ProduitStockMvm::entrees()->count();
        $sortiesFinales = ProduitStockMvm::sorties()->count();

        $this->command->info('📊 Statistiques des mouvements :');
        $this->command->info("📦 Total mouvements créés : {$totalFinal}");
        $this->command->info("📥 Entrées : {$entreesFinales}");
        $this->command->info("📤 Sorties : {$sortiesFinales}");
        $this->command->info('📅 Mouvements récents (7 derniers jours) : '.ProduitStockMvm::recents()->count());
        $this->command->info('✅ Seeding des mouvements terminé avec succès !');
    }

    /**
     * Créer des mouvements spécifiques pour les tests (réduit)
     */
    private function creerMouvementsSpecifiques(): void
    {
        $this->command->info('🎯 Création de mouvements spécifiques...');

        $stocks = ProduitStock::take(3)->get(); // Réduit à 3 stocks

        foreach ($stocks as $stock) {
            // Mouvement d'entrée important
            ProduitStockMvm::factory()
                ->entree()
                ->quantiteImportante()
                ->pourStock($stock)
                ->create([
                    'libelle' => 'Réception commande importante - Test',
                ]);

            // Mouvement de sortie important
            ProduitStockMvm::factory()
                ->sortie()
                ->quantiteImportante()
                ->pourStock($stock)
                ->create([
                    'libelle' => 'Livraison client importante - Test',
                ]);
        }

        $this->command->info('✅ Mouvements spécifiques créés');
    }

    /**
     * Créer des mouvements avec différentes dates (réduit)
     */
    private function creerMouvementsTemporels(): void
    {
        $this->command->info('📅 Création de mouvements temporels...');

        $stocks = ProduitStock::take(5)->get(); // Réduit à 5 stocks

        foreach ($stocks as $stock) {
            // Mouvements récents (dernière semaine) - réduit
            ProduitStockMvm::factory()
                ->count(2) // Réduit de 3 à 2
                ->recent()
                ->pourStock($stock)
                ->create();

            // Mouvements anciens (il y a plusieurs mois) - réduit
            ProduitStockMvm::factory()
                ->count(1) // Réduit de 2 à 1
                ->ancien()
                ->pourStock($stock)
                ->create();
        }

        $this->command->info('✅ Mouvements temporels créés');
    }
}

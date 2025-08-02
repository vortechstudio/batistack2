<?php

namespace Database\Seeders\Produit;

use App\Enums\Produits\TypeMouvementStock;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\ProduitStockMvm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitStockMvmSeeder extends Seeder
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

        // Créer des mouvements pour chaque stock
        foreach ($stocks as $stock) {
            // Nombre de mouvements par stock (entre 3 et 15)
            $nombreMouvements = rand(3, 15);

            for ($i = 0; $i < $nombreMouvements; $i++) {
                // 60% d'entrées, 40% de sorties
                $typeMovement = rand(1, 100) <= 60 ? 'entree' : 'sortie';

                $mouvement = match($typeMovement) {
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

                if ($totalMouvements % 100 === 0) {
                    $this->command->info("✅ {$totalMouvements} mouvements créés...");
                }
            }
        }

        // Créer des mouvements spécifiques
        $this->creerMouvementsSpecifiques();

        // Créer des mouvements récents et anciens
        $this->creerMouvementsTemporels();

        // Statistiques finales
        $totalFinal = ProduitStockMvm::count();
        $entreesFinales = ProduitStockMvm::entrees()->count();
        $sortiesFinales = ProduitStockMvm::sorties()->count();

        $this->command->info("📊 Statistiques des mouvements :");
        $this->command->info("📦 Total mouvements créés : {$totalFinal}");
        $this->command->info("📥 Entrées : {$entreesFinales}");
        $this->command->info("📤 Sorties : {$sortiesFinales}");
        $this->command->info("📅 Mouvements récents (7 derniers jours) : " . ProduitStockMvm::recents()->count());
        $this->command->info("✅ Seeding des mouvements terminé avec succès !");
    }

    /**
     * Créer des mouvements spécifiques pour les tests
     */
    private function creerMouvementsSpecifiques(): void
    {
        $this->command->info('🎯 Création de mouvements spécifiques...');

        $stocks = ProduitStock::take(5)->get();

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

            // Petit mouvement d'ajustement
            ProduitStockMvm::factory()
                ->entree()
                ->petiteQuantite()
                ->pourStock($stock)
                ->create([
                    'libelle' => 'Ajustement inventaire - Test',
                ]);
        }

        $this->command->info('✅ Mouvements spécifiques créés');
    }

    /**
     * Créer des mouvements avec différentes dates
     */
    private function creerMouvementsTemporels(): void
    {
        $this->command->info('📅 Création de mouvements temporels...');

        $stocks = ProduitStock::take(10)->get();

        foreach ($stocks as $stock) {
            // Mouvements récents (dernière semaine)
            ProduitStockMvm::factory()
                ->count(3)
                ->recent()
                ->pourStock($stock)
                ->create();

            // Mouvements anciens (il y a plusieurs mois)
            ProduitStockMvm::factory()
                ->count(2)
                ->ancien()
                ->pourStock($stock)
                ->create();
        }

        $this->command->info('✅ Mouvements temporels créés');
    }
}

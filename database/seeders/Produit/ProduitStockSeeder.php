<?php

namespace Database\Seeders\Produit;

use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📦 Création des stocks de produits...');

        // Récupérer tous les produits et entrepôts
        $produits = Produit::all();
        $entrepots = Entrepot::all();

        if ($produits->isEmpty()) {
            $this->command->warn('Aucun produit trouvé. Veuillez d\'abord exécuter ProduitSeeder.');
            return;
        }

        if ($entrepots->isEmpty()) {
            $this->command->warn('Aucun entrepôt trouvé. Veuillez d\'abord exécuter EntrepotSeeder.');
            return;
        }

        $totalStocks = 0;
        $stocksEnRupture = 0;
        $stocksCritiques = 0;
        $stocksNormaux = 0;

        // Créer des stocks pour chaque produit dans chaque entrepôt
        foreach ($produits as $produit) {
            foreach ($entrepots as $entrepot) {
                // 70% de chance d'avoir du stock dans cet entrepôt
                if (rand(1, 100) <= 70) {
                    // Déterminer le type de stock
                    $typeStock = $this->determinerTypeStock();

                    $stock = match($typeStock) {
                        'rupture' => ProduitStock::factory()
                            ->enRupture()
                            ->pourProduit($produit)
                            ->pourEntrepot($entrepot)
                            ->create(),
                        'critique' => ProduitStock::factory()
                            ->stockCritique()
                            ->pourProduit($produit)
                            ->pourEntrepot($entrepot)
                            ->create(),
                        'faible' => ProduitStock::factory()
                            ->stockFaible()
                            ->pourProduit($produit)
                            ->pourEntrepot($entrepot)
                            ->create(),
                        'normal' => ProduitStock::factory()
                            ->stockNormal()
                            ->pourProduit($produit)
                            ->pourEntrepot($entrepot)
                            ->create(),
                        'eleve' => ProduitStock::factory()
                            ->stockEleve()
                            ->pourProduit($produit)
                            ->pourEntrepot($entrepot)
                            ->create(),
                    };

                    $totalStocks++;

                    // Compter par type
                    match($typeStock) {
                        'rupture' => $stocksEnRupture++,
                        'critique' => $stocksCritiques++,
                        default => $stocksNormaux++,
                    };

                    if ($totalStocks % 50 === 0) {
                        $this->command->info("✅ {$totalStocks} stocks créés...");
                    }
                }
            }
        }

        // Créer quelques stocks spécifiques pour les tests
        $this->creerStocksSpecifiques();

        // Statistiques finales
        $this->command->info("📊 Statistiques des stocks :");
        $this->command->info("📦 Total stocks créés : {$totalStocks}");
        $this->command->info("🔴 Stocks en rupture : {$stocksEnRupture}");
        $this->command->info("🟡 Stocks critiques : {$stocksCritiques}");
        $this->command->info("🟢 Stocks normaux/élevés : {$stocksNormaux}");
        $this->command->info("✅ Seeding des stocks terminé avec succès !");
    }

    /**
     * Déterminer le type de stock selon une répartition
     */
    private function determinerTypeStock(): string
    {
        $rand = rand(1, 100);

        return match(true) {
            $rand <= 5 => 'rupture',      // 5%
            $rand <= 15 => 'critique',    // 10%
            $rand <= 30 => 'faible',      // 15%
            $rand <= 80 => 'normal',      // 50%
            default => 'eleve',           // 20%
        };
    }

    /**
     * Créer des stocks spécifiques pour les tests
     */
    private function creerStocksSpecifiques(): void
    {
        $this->command->info('🎯 Création de stocks spécifiques...');

        $entrepotPrincipal = Entrepot::first();
        $produits = Produit::take(10)->get();

        foreach ($produits as $produit) {
            // Stock élevé pour les 5 premiers produits
            if ($produits->search($produit) < 5) {
                ProduitStock::factory()
                    ->stockEleve()
                    ->pourProduit($produit)
                    ->pourEntrepot($entrepotPrincipal)
                    ->create();
            } else {
                // Stock critique pour les autres
                ProduitStock::factory()
                    ->stockCritique()
                    ->pourProduit($produit)
                    ->pourEntrepot($entrepotPrincipal)
                    ->create();
            }
        }

        $this->command->info('✅ Stocks spécifiques créés');
    }
}

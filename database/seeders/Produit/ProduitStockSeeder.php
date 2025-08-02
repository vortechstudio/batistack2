<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use Illuminate\Database\Seeder;

final class ProduitStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📦 Création des stocks de produits...');

        // Récupérer un échantillon réduit de produits et entrepôts
        $produits = Produit::take(20)->get(); // Limité à 20 produits
        $entrepots = Entrepot::take(5)->get(); // Limité à 5 entrepôts

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

        // Créer des stocks pour un échantillon de produits dans quelques entrepôts
        foreach ($produits as $produit) {
            // Chaque produit n'aura du stock que dans 1 à 3 entrepôts maximum
            $entrepotsSelectionnes = $entrepots->random(rand(1, min(3, $entrepots->count())));

            foreach ($entrepotsSelectionnes as $entrepot) {
                // Déterminer le type de stock
                $typeStock = $this->determinerTypeStock();

                $stock = match ($typeStock) {
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
                match ($typeStock) {
                    'rupture' => $stocksEnRupture++,
                    'critique' => $stocksCritiques++,
                    default => $stocksNormaux++,
                };
            }
        }

        // Créer quelques stocks spécifiques pour les tests (réduit)
        $this->creerStocksSpecifiques();

        // Statistiques finales
        $this->command->info('📊 Statistiques des stocks :');
        $this->command->info("📦 Total stocks créés : {$totalStocks}");
        $this->command->info("🔴 Stocks en rupture : {$stocksEnRupture}");
        $this->command->info("🟡 Stocks critiques : {$stocksCritiques}");
        $this->command->info("🟢 Stocks normaux/élevés : {$stocksNormaux}");
        $this->command->info('✅ Seeding des stocks terminé avec succès !');
    }

    /**
     * Déterminer le type de stock selon une répartition
     */
    private function determinerTypeStock(): string
    {
        $rand = rand(1, 100);

        return match (true) {
            $rand <= 5 => 'rupture',      // 5%
            $rand <= 15 => 'critique',    // 10%
            $rand <= 30 => 'faible',      // 15%
            $rand <= 80 => 'normal',      // 50%
            default => 'eleve',           // 20%
        };
    }

    /**
     * Créer des stocks spécifiques pour les tests (version réduite)
     */
    private function creerStocksSpecifiques(): void
    {
        $this->command->info('🎯 Création de stocks spécifiques...');

        $entrepotPrincipal = Entrepot::first();
        $produits = Produit::take(5)->get(); // Réduit à 5 produits

        foreach ($produits as $produit) {
            // Éviter les doublons - vérifier si le stock existe déjà
            $stockExistant = ProduitStock::where('produit_id', $produit->id)
                ->where('entrepot_id', $entrepotPrincipal->id)
                ->exists();

            if (! $stockExistant) {
                // Stock élevé pour les 3 premiers produits
                if ($produits->search($produit) < 3) {
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
        }

        $this->command->info('✅ Stocks spécifiques créés');
    }
}

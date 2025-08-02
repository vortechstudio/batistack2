<?php

namespace Database\Seeders\Produit;

use App\Models\Produit\Produit;
use App\Models\Produit\TarifFournisseur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifFournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Création des tarifs fournisseurs...');

        // Vérifier que les produits existent
        $produits = Produit::all();

        if ($produits->isEmpty()) {
            $this->command->warn('Aucun produit trouvé. Veuillez d\'abord exécuter ProduitSeeder.');
            return;
        }

        // Tarifs fournisseurs spécifiques avec données fixes
        $tarifsSpecifiques = [
            [
                'ref_fournisseur' => 'REF-00000001',
                'qte_minimal' => 50.00,
                'prix_unitaire' => 12.50,
                'delai_livraison' => 5,
                'barrecode' => '3760123456789',
                'produit_id' => $produits->where('reference', 'PRD-000001')->first()?->id ?? $produits->random()->id,
            ],
            [
                'ref_fournisseur' => 'ART-00000002',
                'qte_minimal' => 10.00,
                'prix_unitaire' => 8.75,
                'delai_livraison' => 3,
                'barrecode' => '3760123456796',
                'produit_id' => $produits->where('reference', 'PRD-000002')->first()?->id ?? $produits->random()->id,
            ],
            [
                'ref_fournisseur' => 'SKU-00000003',
                'qte_minimal' => 5.00,
                'prix_unitaire' => 45.20,
                'delai_livraison' => 7,
                'barrecode' => '3760123456802',
                'produit_id' => $produits->where('reference', 'PRD-000003')->first()?->id ?? $produits->random()->id,
            ],
            [
                'ref_fournisseur' => 'PROD-00000004',
                'qte_minimal' => 1.00,
                'prix_unitaire' => 125.00,
                'delai_livraison' => 2,
                'barrecode' => '3760123456819',
                'produit_id' => $produits->where('reference', 'PRD-000004')->first()?->id ?? $produits->random()->id,
            ],
        ];

        foreach ($tarifsSpecifiques as $tarifData) {
            TarifFournisseur::create($tarifData);
            $this->command->info("✅ Tarif fournisseur créé : {$tarifData['ref_fournisseur']}");
        }

        // Créer seulement 1 tarif par produit (au lieu de 1-4)
        $totalTarifsGeneres = 0;

        foreach ($produits as $produit) {
            // Seulement 60% des produits ont un tarif fournisseur
            if (rand(1, 100) <= 60) {
                TarifFournisseur::factory()
                    ->pourProduit($produit->id)
                    ->create();

                $totalTarifsGeneres++;
            }
        }

        $this->command->info("📦 {$totalTarifsGeneres} tarifs générés pour les produits existants");

        // Créer seulement quelques tarifs spécialisés
        $tarifsSpecialises = [
            // Tarifs pour matériaux de construction
            TarifFournisseur::factory()->count(4)->materiauConstruction()->create(),
            // Tarifs pour outillage
            TarifFournisseur::factory()->count(3)->outillage()->create(),
            // Tarifs avec livraison rapide
            TarifFournisseur::factory()->count(2)->livraisonRapide()->create(),
            // Tarifs économiques
            TarifFournisseur::factory()->count(3)->economique()->create(),
            // Tarifs avec code-barres
            TarifFournisseur::factory()->count(5)->avecCodeBarre()->create(),
        ];

        $totalSpecialises = array_sum(array_map('count', $tarifsSpecialises));
        $totalTarifsGeneres += $totalSpecialises;

        $this->command->info("🎯 {$totalSpecialises} tarifs spécialisés créés");

        // Statistiques finales
        $totalTarifs = TarifFournisseur::count();
        $tarifsAvecCodeBarre = TarifFournisseur::whereNotNull('barrecode')->count();
        $tarifsLivraisonRapide = TarifFournisseur::livraisonRapide(3)->count();
        $tarifsQuantiteFaible = TarifFournisseur::quantiteMinimaleFaible(5)->count();

        $this->command->info("📊 === STATISTIQUES TARIFS FOURNISSEURS ===");
        $this->command->info("💰 Total tarifs créés : {$totalTarifs}");
        $this->command->info("📊 Avec code-barres : {$tarifsAvecCodeBarre}");
        $this->command->info("🚚 Livraison rapide (≤3j) : {$tarifsLivraisonRapide}");
        $this->command->info("📦 Quantité minimale faible (≤5) : {$tarifsQuantiteFaible}");
        $this->command->info("🔧 Tarifs spécifiques : " . count($tarifsSpecifiques));
        $this->command->info("🎲 Tarifs générés aléatoirement : {$totalTarifsGeneres}");
        $this->command->info("✅ Seeding des tarifs fournisseurs terminé avec succès !");
    }
}

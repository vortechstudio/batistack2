<?php

namespace Database\Seeders\Produit;

use App\Enums\Produits\TauxTVA;
use App\Models\Produit\Produit;
use App\Models\Produit\Service;
use App\Models\Produit\TarifClient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Création des tarifs clients...');

        // Vérifier que les produits et services existent
        $produits = Produit::all();
        $services = Service::all();

        if ($produits->isEmpty() && $services->isEmpty()) {
            $this->command->warn('Aucun produit ou service trouvé. Veuillez d\'abord exécuter ProduitSeeder et ServiceSeeder.');
            return;
        }

        // Tarifs clients spécifiques avec données fixes
        $tarifsSpecifiques = [
            // Tarifs pour produits
            [
                'prix_unitaire' => 18.50,
                'taux_tva' => TauxTVA::NORMAL->value,
                'produit_id' => $produits->where('reference', 'PRD-000001')->first()?->id ?? $produits->random()->id,
                'service_id' => null,
            ],
            [
                'prix_unitaire' => 12.90,
                'taux_tva' => TauxTVA::NORMAL->value,
                'produit_id' => $produits->where('reference', 'PRD-000002')->first()?->id ?? $produits->random()->id,
                'service_id' => null,
            ],
            [
                'prix_unitaire' => 65.00,
                'taux_tva' => TauxTVA::NORMAL->value,
                'produit_id' => $produits->where('reference', 'PRD-000003')->first()?->id ?? $produits->random()->id,
                'service_id' => null,
            ],
            [
                'prix_unitaire' => 189.00,
                'taux_tva' => TauxTVA::NORMAL->value,
                'produit_id' => $produits->where('reference', 'PRD-000004')->first()?->id ?? $produits->random()->id,
                'service_id' => null,
            ],
        ];

        // Ajouter des tarifs pour services si ils existent
        if (!$services->isEmpty()) {
            $tarifsSpecifiques = array_merge($tarifsSpecifiques, [
                [
                    'prix_unitaire' => 450.00,
                    'taux_tva' => TauxTVA::REDUIT_5_5->value, // TVA réduite pour rénovation
                    'produit_id' => null,
                    'service_id' => $services->where('reference', 'SRV-000001')->first()?->id ?? $services->random()->id,
                ],
                [
                    'prix_unitaire' => 35.00,
                    'taux_tva' => TauxTVA::INTERMEDIAIRE->value, // TVA intermédiaire pour amélioration
                    'produit_id' => null,
                    'service_id' => $services->where('reference', 'SRV-000002')->first()?->id ?? $services->random()->id,
                ],
                [
                    'prix_unitaire' => 850.00,
                    'taux_tva' => TauxTVA::REDUIT_5_5->value,
                    'produit_id' => null,
                    'service_id' => $services->where('reference', 'SRV-000003')->first()?->id ?? $services->random()->id,
                ],
            ]);
        }

        foreach ($tarifsSpecifiques as $tarifData) {
            TarifClient::create($tarifData);
            $elementType = $tarifData['produit_id'] ? 'Produit' : 'Service';
            $elementId = $tarifData['produit_id'] ?? $tarifData['service_id'];
            $this->command->info("✅ Tarif client créé : {$elementType} ID {$elementId} - {$tarifData['prix_unitaire']}€ HT");
        }

        // Créer des tarifs pour chaque produit
        $totalTarifsGeneres = 0;

        if (!$produits->isEmpty()) {
            foreach ($produits as $produit) {
                // 80% des produits ont un tarif client
                if (rand(1, 100) <= 80) {
                    TarifClient::factory()
                        ->pourProduit($produit->id)
                        ->create();

                    $totalTarifsGeneres++;
                }
            }

            $this->command->info("📦 Tarifs générés pour les produits : " . $produits->count() * 0.8);
        }

        // Créer des tarifs pour chaque service
        if (!$services->isEmpty()) {
            foreach ($services as $service) {
                // 90% des services ont un tarif client
                if (rand(1, 100) <= 90) {
                    TarifClient::factory()
                        ->pourService($service->id)
                        ->create();

                    $totalTarifsGeneres++;
                }
            }

            $this->command->info("🔧 Tarifs générés pour les services : " . $services->count() * 0.9);
        }

        // Créer des tarifs spécialisés
        $tarifsSpecialises = [
            // Tarifs économiques
            TarifClient::factory()->count(15)->economique()->create(),
            // Tarifs standard
            TarifClient::factory()->count(25)->standard()->create(),
            // Tarifs premium
            TarifClient::factory()->count(8)->premium()->create(),
            // Tarifs pour matériaux de construction
            TarifClient::factory()->count(20)->materiauConstruction()->create(),
            // Tarifs pour services de rénovation (TVA réduite)
            TarifClient::factory()->count(12)->serviceRenovation()->create(),
            // Tarifs pour services d'amélioration (TVA intermédiaire)
            TarifClient::factory()->count(10)->serviceAmelioration()->create(),
            // Tarifs avec TVA normale
            TarifClient::factory()->count(30)->tvaNormale()->create(),
            // Tarifs avec TVA réduite
            TarifClient::factory()->count(8)->tvaReduite()->create(),
            // Tarifs avec TVA intermédiaire
            TarifClient::factory()->count(6)->tvaIntermediaire()->create(),
            // Tarifs exonérés de TVA
            TarifClient::factory()->count(3)->tvaZero()->create(),
        ];

        $totalSpecialises = array_sum(array_map('count', $tarifsSpecialises));
        $totalTarifsGeneres += $totalSpecialises;

        $this->command->info("🎯 {$totalSpecialises} tarifs spécialisés créés");

        // Statistiques finales
        $totalTarifs = TarifClient::count();
        $tarifsProduits = TarifClient::pourProduits()->count();
        $tarifsServices = TarifClient::pourServices()->count();
        $tarifsTVANormale = TarifClient::avecTauxTVA(TauxTVA::NORMAL->value)->count();
        $tarifsTVAReduite = TarifClient::avecTauxTVA(TauxTVA::REDUIT_5_5->value)->count();
        $tarifsTVAIntermediaire = TarifClient::avecTauxTVA(TauxTVA::INTERMEDIAIRE->value)->count();

        $this->command->info("📊 === STATISTIQUES TARIFS CLIENTS ===");
        $this->command->info("💰 Total tarifs créés : {$totalTarifs}");
        $this->command->info("📦 Tarifs produits : {$tarifsProduits}");
        $this->command->info("🔧 Tarifs services : {$tarifsServices}");
        $this->command->info("📊 TVA normale (20%) : {$tarifsTVANormale}");
        $this->command->info("🏠 TVA réduite (5,5%) : {$tarifsTVAReduite}");
        $this->command->info("🔨 TVA intermédiaire (10%) : {$tarifsTVAIntermediaire}");
        $this->command->info("🔧 Tarifs spécifiques : " . count($tarifsSpecifiques));
        $this->command->info("🎲 Tarifs générés aléatoirement : {$totalTarifsGeneres}");
        $this->command->info("✅ Seeding des tarifs clients terminé avec succès !");
    }
}

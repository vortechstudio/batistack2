<?php

namespace Database\Seeders\Produit;

use App\Models\Produit\Category;
use App\Models\Produit\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔧 Création des services...');

        // Récupérer les catégories existantes
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('Aucune catégorie trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        // Services spécifiques avec données fixes (réduit à 4)
        $servicesSpecifiques = [
            [
                'reference' => 'SRV-000001',
                'name' => 'Installation plomberie salle de bain complète',
                'description' => 'Service d\'installation complète de plomberie pour salle de bain : pose sanitaires, raccordements eau chaude/froide, évacuations, robinetterie. Main d\'œuvre qualifiée avec garantie 2 ans.',
                'category_id' => $categories->where('name', 'like', '%Plomberie%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000002',
                'name' => 'Installation électrique résidentielle',
                'description' => 'Installation électrique complète pour logement : tableau électrique, circuits prises et éclairage, mise à la terre. Conforme NF C 15-100.',
                'category_id' => $categories->where('name', 'like', '%Électricité%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000003',
                'name' => 'Maçonnerie générale',
                'description' => 'Travaux de maçonnerie générale : montage murs, cloisons, enduits, petites réparations. Matériaux et outillage inclus.',
                'category_id' => $categories->where('name', 'like', '%Maçonnerie%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000004',
                'name' => 'Diagnostic technique bâtiment',
                'description' => 'Diagnostic complet de l\'état du bâtiment : structure, étanchéité, isolation, installations. Rapport détaillé avec recommandations.',
                'category_id' => $categories->random()->id,
            ],
        ];

        foreach ($servicesSpecifiques as $serviceData) {
            Service::create($serviceData);
            $this->command->info("✅ Service créé : {$serviceData['name']}");
        }

        // Générer seulement 1-2 services par catégorie principale
        $totalServicesGeneres = 0;
        $categoriesPrincipales = $categories->whereNull('category_id');

        foreach ($categoriesPrincipales as $category) {
            // Seulement 1-2 services par catégorie principale
            $nombreServices = rand(1, 2);

            Service::factory()
                ->count($nombreServices)
                ->pourCategorie($category->id)
                ->create();

            $totalServicesGeneres += $nombreServices;
            $this->command->info("📦 {$nombreServices} services créés pour la catégorie : {$category->name}");
        }

        // Créer seulement quelques services spécialisés
        $servicesSpecialises = [
            // Services de construction
            Service::factory()->count(2)->construction()->create(),
            // Services de rénovation
            Service::factory()->count(2)->renovation()->create(),
        ];

        $totalSpecialises = array_sum(array_map('count', $servicesSpecialises));
        $totalServicesGeneres += $totalSpecialises;

        $this->command->info("🎯 {$totalSpecialises} services spécialisés créés");

        // Statistiques finales
        $totalServices = Service::count();
        $this->command->info("📊 === STATISTIQUES SERVICES ===");
        $this->command->info("📦 Total services créés : {$totalServices}");
        $this->command->info("🔧 Services spécifiques : " . count($servicesSpecifiques));
        $this->command->info("🎲 Services générés aléatoirement : {$totalServicesGeneres}");
        $this->command->info("✅ Seeding des services terminé avec succès !");
    }
}

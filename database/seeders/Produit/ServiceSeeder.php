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

        // Services spécifiques avec données fixes
        $servicesSpecifiques = [
            [
                'reference' => 'SRV-000001',
                'name' => 'Installation plomberie salle de bain complète',
                'description' => 'Service d\'installation complète de plomberie pour salle de bain : pose sanitaires, raccordements eau chaude/froide, évacuations, robinetterie. Main d\'œuvre qualifiée avec garantie 2 ans.',
                'category_id' => $categories->where('name', 'like', '%Plomberie%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000002',
                'name' => 'Pose carrelage au m² - Main d\'œuvre',
                'description' => 'Service de pose de carrelage par artisan qualifié. Prix au m². Fourniture colle et joints incluse. Préparation support comprise.',
                'category_id' => $categories->where('name', 'like', '%Carrelage%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000003',
                'name' => 'Installation électrique résidentielle',
                'description' => 'Installation électrique complète pour logement : tableau électrique, circuits prises et éclairage, mise à la terre. Conforme NF C 15-100.',
                'category_id' => $categories->where('name', 'like', '%Électricité%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000004',
                'name' => 'Peinture intérieure 2 couches',
                'description' => 'Service de peinture intérieure : préparation des supports, application sous-couche et 2 couches de finition. Peinture acrylique haute qualité.',
                'category_id' => $categories->where('name', 'like', '%Peinture%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000005',
                'name' => 'Maçonnerie générale',
                'description' => 'Travaux de maçonnerie générale : montage murs, cloisons, enduits, petites réparations. Matériaux et outillage inclus.',
                'category_id' => $categories->where('name', 'like', '%Maçonnerie%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000006',
                'name' => 'Diagnostic technique bâtiment',
                'description' => 'Diagnostic complet de l\'état du bâtiment : structure, étanchéité, isolation, installations. Rapport détaillé avec recommandations.',
                'category_id' => $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000007',
                'name' => 'Nettoyage fin de chantier',
                'description' => 'Service de nettoyage complet en fin de chantier : évacuation gravats, nettoyage sols et surfaces, remise en état. Équipe spécialisée.',
                'category_id' => $categories->random()->id,
            ],
        ];

        foreach ($servicesSpecifiques as $serviceData) {
            Service::create($serviceData);
            $this->command->info("✅ Service créé : {$serviceData['name']}");
        }

        // Générer des services aléatoires par catégorie
        $totalServicesGeneres = 0;

        foreach ($categories as $category) {
            // Nombre de services par catégorie (entre 3 et 8)
            $nombreServices = rand(3, 8);

            // Créer les services pour cette catégorie
            Service::factory()
                ->count($nombreServices)
                ->pourCategorie($category->id)
                ->create();

            $totalServicesGeneres += $nombreServices;
            $this->command->info("📦 {$nombreServices} services créés pour la catégorie : {$category->name}");
        }

        // Créer quelques services spécialisés
        $servicesSpecialises = [
            // Services de construction
            Service::factory()->count(5)->construction()->create(),
            // Services de rénovation
            Service::factory()->count(4)->renovation()->create(),
            // Services de finition
            Service::factory()->count(6)->finition()->create(),
            // Services avec descriptions complètes
            Service::factory()->count(3)->avecDescriptionComplete()->create(),
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

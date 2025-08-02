<?php

namespace Database\Seeders;

use App\Models\Produit\Category;
use App\Models\Produit\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // S'assurer qu'il y a des catégories disponibles
        if (Category::count() === 0) {
            $this->command->warn('Aucune catégorie trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        // Récupérer quelques catégories pour les produits spécifiques
        $categories = Category::all();
        $categorieGrosOeuvre = $categories->where('name', 'Gros œuvre')->first();
        $categoriePlomberie = $categories->where('name', 'Plomberie')->first();
        $categorieElectricite = $categories->where('name', 'Électricité')->first();
        $categorieMenuiserie = $categories->where('name', 'Menuiserie')->first();
        $categorieOutillage = $categories->where('name', 'Outillage')->first();

        // Créer des produits spécifiques avec des données fixes
        $produitsSpecifiques = [
            // Gros œuvre
            [
                'type' => 'produit',
                'reference' => 'PRD-000001',
                'name' => 'Ciment Portland CEM II/A-LL 32,5 R - Sac 35kg',
                'description' => 'Ciment Portland composé conforme à la norme NF EN 197-1. Idéal pour bétons, mortiers et enduits. Résistance 32,5 MPa. Conditionnement sac de 35kg.',
                'achat' => true,
                'vente' => true,
                'category_id' => $categorieGrosOeuvre?->id ?? $categories->random()->id,
            ],
            [
                'type' => 'produit',
                'reference' => 'PRD-000002',
                'name' => 'Parpaing béton creux 20x20x50 cm',
                'description' => 'Bloc béton creux traditionnel pour murs porteurs et cloisons. Dimensions 20x20x50 cm. Résistance thermique optimisée.',
                'achat' => true,
                'vente' => true,
                'category_id' => $categorieGrosOeuvre?->id ?? $categories->random()->id,
            ],

            // Plomberie
            [
                'type' => 'produit',
                'reference' => 'PRD-000003',
                'name' => 'Tube PVC évacuation Ø100 mm - Longueur 3m',
                'description' => 'Tube PVC rigide pour évacuation eaux usées. Diamètre 100mm, longueur 3m. Conforme NF EN 1329.',
                'achat' => true,
                'vente' => true,
                'category_id' => $categoriePlomberie?->id ?? $categories->random()->id,
            ],
            [
                'type' => 'produit',
                'reference' => 'PRD-000004',
                'name' => 'Mitigeur lavabo chromé avec vidage',
                'description' => 'Mitigeur monocommande pour lavabo. Finition chromée brillante. Cartouche céramique 40mm. Livré avec vidage automatique.',
                'achat' => true,
                'vente' => true,
                'category_id' => $categoriePlomberie?->id ?? $categories->random()->id,
            ],

            // Électricité
            [
                'type' => 'produit',
                'reference' => 'PRD-000005',
                'name' => 'Câble électrique U1000R2V 3G2,5 mm² - Couronne 100m',
                'description' => 'Câble électrique rigide pour installation domestique. Section 3x2,5mm². Isolation PVC. Couronne de 100 mètres.',
                'achat' => true,
                'vente' => true,
                'category_id' => $categorieElectricite?->id ?? $categories->random()->id,
            ],

            // Services
            [
                'type' => 'service',
                'reference' => 'SRV-000001',
                'name' => 'Installation complète plomberie salle de bain',
                'description' => 'Service d\'installation complète de plomberie pour salle de bain : pose sanitaires, raccordements, évacuations. Main d\'œuvre qualifiée.',
                'achat' => false,
                'vente' => true,
                'category_id' => $categoriePlomberie?->id ?? $categories->random()->id,
            ],
            [
                'type' => 'service',
                'reference' => 'SRV-000002',
                'name' => 'Pose carrelage au m² - Main d\'œuvre',
                'description' => 'Service de pose de carrelage par artisan qualifié. Prix au m². Fourniture colle et joints incluse.',
                'achat' => false,
                'vente' => true,
                'category_id' => $categories->where('name', 'Carrelage')->first()?->id ?? $categories->random()->id,
            ],
        ];

        foreach ($produitsSpecifiques as $produit) {
            Produit::create($produit);
        }

        // Créer des produits aléatoires répartis par catégorie
        foreach ($categories->take(10) as $category) {
            // 8-12 produits par catégorie
            $nombreProduits = rand(8, 12);

            // 70% de produits, 30% de services
            $nombreServices = (int) ($nombreProduits * 0.3);
            $nombreProduitsPhysiques = $nombreProduits - $nombreServices;

            // Créer les produits physiques
            Produit::factory()
                ->count($nombreProduitsPhysiques)
                ->produit()
                ->pourCategorie($category->id)
                ->create();

            // Créer les services
            Produit::factory()
                ->count($nombreServices)
                ->service()
                ->pourCategorie($category->id)
                ->create();
        }

        // Créer quelques produits avec des états spécifiques
        Produit::factory()->count(5)->nonDisponibleAchat()->create();
        Produit::factory()->count(3)->nonDisponibleVente()->create();
        Produit::factory()->count(10)->avecDescription()->create();

        // Créer des produits supplémentaires pour les catégories restantes
        $categoriesRestantes = $categories->skip(10);
        foreach ($categoriesRestantes as $category) {
            Produit::factory()
                ->count(rand(3, 6))
                ->pourCategorie($category->id)
                ->create();
        }

        $this->command->info('Produits créés avec succès !');
        $this->command->info('Total produits : ' . Produit::count());
        $this->command->info('Produits physiques : ' . Produit::where('type', 'produit')->count());
        $this->command->info('Services : ' . Produit::where('type', 'service')->count());
        $this->command->info('Disponibles à l\'achat : ' . Produit::where('achat', true)->count());
        $this->command->info('Disponibles à la vente : ' . Produit::where('vente', true)->count());
    }
}

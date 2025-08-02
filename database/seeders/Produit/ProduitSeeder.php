<?php

namespace Database\Seeders\Produit;

use App\Enums\Produits\TypeProduit;
use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
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
        $this->command->info('📦 Création des produits...');

        // Vérifier que les catégories et entrepôts existent
        $categories = Category::all();
        $entrepots = Entrepot::all();

        if ($categories->isEmpty()) {
            $this->command->warn('Aucune catégorie trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        if ($entrepots->isEmpty()) {
            $this->command->warn('Aucun entrepôt trouvé. Veuillez d\'abord exécuter EntrepotSeeder.');
            return;
        }

        // Récupérer des catégories spécifiques pour les produits fixes
        $categorieGrosOeuvre = $categories->where('name', 'like', '%Gros%')->first();
        $categoriePlomberie = $categories->where('name', 'like', '%Plomberie%')->first();
        $categorieElectricite = $categories->where('name', 'like', '%Électricité%')->first();
        $categorieOutillage = $categories->where('name', 'like', '%Outillage%')->first();
        $entrepotPrincipal = $entrepots->where('name', 'like', '%Principal%')->first() ?? $entrepots->first();

        // Produits spécifiques avec données fixes
        $produitsSpecifiques = [
            [
                'reference' => 'PRD-000001',
                'name' => 'Ciment Portland CEM II 32,5 - Sac 35kg',
                'description' => 'Ciment Portland composé CEM II/A-LL 32,5 R conforme à la norme NF EN 197-1. Idéal pour bétons et mortiers courants.',
                'achat' => true,
                'vente' => true,
                'serial_number' => null,
                'limit_stock' => 50.00,
                'optimal_stock' => 200.00,
                'poids_value' => 35.00,
                'poids_unite' => UnitePoids::KILOGRAMME->value,
                'longueur' => 600.00,
                'largeur' => 400.00,
                'hauteur' => 120.00,
                'llh_unite' => UniteMesure::MILLIMETRE->value,
                'category_id' => $categorieGrosOeuvre?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
            [
                'reference' => 'PRD-000002',
                'name' => 'Tube PVC évacuation Ø100 - Longueur 3m',
                'description' => 'Tube PVC rigide pour évacuation eaux usées. Diamètre 100mm, longueur 3 mètres. Conforme NF.',
                'achat' => true,
                'vente' => true,
                'serial_number' => null,
                'limit_stock' => 20.00,
                'optimal_stock' => 100.00,
                'poids_value' => 2.50,
                'poids_unite' => UnitePoids::KILOGRAMME->value,
                'longueur' => 3000.00,
                'largeur' => 100.00,
                'hauteur' => 100.00,
                'llh_unite' => UniteMesure::MILLIMETRE->value,
                'category_id' => $categoriePlomberie?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
            [
                'reference' => 'PRD-000003',
                'name' => 'Câble électrique 3G2,5 - Couronne 100m',
                'description' => 'Câble électrique souple 3x2,5mm² avec terre. Isolation PVC. Couronne de 100 mètres.',
                'achat' => true,
                'vente' => true,
                'serial_number' => null,
                'limit_stock' => 10.00,
                'optimal_stock' => 50.00,
                'poids_value' => 15.00,
                'poids_unite' => UnitePoids::KILOGRAMME->value,
                'longueur' => 400.00,
                'largeur' => 400.00,
                'hauteur' => 200.00,
                'llh_unite' => UniteMesure::MILLIMETRE->value,
                'category_id' => $categorieElectricite?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
            [
                'reference' => 'PRD-000004',
                'name' => 'Perceuse visseuse 18V Li-Ion',
                'description' => 'Perceuse visseuse sans fil 18V avec batterie lithium-ion 2Ah. Couple 50Nm. Mandrin auto-serrant 13mm.',
                'achat' => true,
                'vente' => true,
                'serial_number' => 'PV18-2024-001',
                'limit_stock' => 5.00,
                'optimal_stock' => 20.00,
                'poids_value' => 1.80,
                'poids_unite' => UnitePoids::KILOGRAMME->value,
                'longueur' => 250.00,
                'largeur' => 80.00,
                'hauteur' => 220.00,
                'llh_unite' => UniteMesure::MILLIMETRE->value,
                'category_id' => $categorieOutillage?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
        ];

        foreach ($produitsSpecifiques as $produitData) {
            Produit::create($produitData);
            $this->command->info("✅ Produit créé : {$produitData['name']}");
        }

        // Générer des produits aléatoires par catégorie et entrepôt
        $totalProduitsGeneres = 0;

        foreach ($categories as $category) {
            foreach ($entrepots as $entrepot) {
                // Nombre de produits par catégorie/entrepôt (entre 5 et 15)
                $nombreProduits = rand(5, 15);

                // Répartition : 80% produits physiques, 20% services (mais les services sont dans une autre table maintenant)
                // Donc on ne crée que des produits physiques ici
                Produit::factory()
                    ->count($nombreProduits)
                    ->produit()
                    ->pourCategorie($category->id)
                    ->pourEntrepot($entrepot->id)
                    ->create();

                $totalProduitsGeneres += $nombreProduits;
            }

            $this->command->info("📦 Produits créés pour la catégorie : {$category->name}");
        }

        // Créer quelques produits spécialisés
        $produitsSpecialises = [
            // Matériaux de construction lourds
            Produit::factory()->count(15)->materiauConstruction()->create(),
            // Outillage léger
            Produit::factory()->count(20)->outillage()->create(),
            // Produits avec dimensions spécifiques
            Produit::factory()->count(10)->avecDimensions()->create(),
            // Produits avec gestion de stock
            Produit::factory()->count(12)->avecStock()->create(),
            // Produits non disponibles à l'achat
            Produit::factory()->count(5)->nonDisponibleAchat()->create(),
            // Produits avec descriptions complètes
            Produit::factory()->count(8)->state(['description' => function() {
                return fake()->paragraphs(3, true) . "\n\nCaractéristiques techniques :\n" .
                    "- " . implode("\n- ", fake()->sentences(4));
            }])->create(),
        ];

        $totalSpecialises = array_sum(array_map('count', $produitsSpecialises));
        $totalProduitsGeneres += $totalSpecialises;

        $this->command->info("🎯 {$totalSpecialises} produits spécialisés créés");

        // Statistiques finales
        $totalProduits = Produit::count();
        $produitsDisponiblesAchat = Produit::disponibleAchat()->count();
        $produitsDisponiblesVente = Produit::disponibleVente()->count();
        $produitsAvecStock = Produit::where('limit_stock', '>', 0)->count();

        $this->command->info("📊 === STATISTIQUES PRODUITS ===");
        $this->command->info("📦 Total produits créés : {$totalProduits}");
        $this->command->info("🛒 Disponibles à l'achat : {$produitsDisponiblesAchat}");
        $this->command->info("💰 Disponibles à la vente : {$produitsDisponiblesVente}");
        $this->command->info("📊 Avec gestion de stock : {$produitsAvecStock}");
        $this->command->info("🏗️ Produits spécifiques : " . count($produitsSpecifiques));
        $this->command->info("🎲 Produits générés aléatoirement : {$totalProduitsGeneres}");
        $this->command->info("✅ Seeding des produits terminé avec succès !");
    }
}

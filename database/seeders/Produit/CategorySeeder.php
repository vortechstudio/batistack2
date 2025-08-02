<?php

namespace Database\Seeders\Produit;

use App\Models\Produit\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les catégories principales
        $categoriesPrincipales = [
            'Gros œuvre' => [
                'Béton et mortier',
                'Maçonnerie',
                'Ferraillage',
                'Coffrages',
                'Étanchéité',
            ],
            'Second œuvre' => [
                'Cloisons',
                'Isolation',
                'Plâtrerie',
                'Menuiserie intérieure',
                'Revêtements muraux',
            ],
            'Plomberie' => [
                'Tubes et raccords',
                'Robinetterie',
                'Sanitaires',
                'Chauffage',
                'Évacuation',
            ],
            'Électricité' => [
                'Câbles et fils',
                'Appareillage',
                'Tableaux électriques',
                'Éclairage',
                'Domotique',
            ],
            'Menuiserie' => [
                'Portes',
                'Fenêtres',
                'Volets',
                'Escaliers',
                'Parquets',
            ],
            'Carrelage' => [
                'Carreaux sol',
                'Carreaux mur',
                'Faïence',
                'Mosaïque',
                'Accessoires pose',
            ],
            'Peinture' => [
                'Peinture intérieure',
                'Peinture extérieure',
                'Enduits',
                'Lasures et vernis',
                'Accessoires peinture',
            ],
            'Outillage' => [
                'Outillage électroportatif',
                'Outillage à main',
                'Machines de chantier',
                'Équipements de mesure',
                'Consommables',
            ],
            'Équipements de sécurité' => [
                'Protection individuelle',
                'Signalisation',
                'Échafaudages',
                'Garde-corps',
                'Équipements collectifs',
            ],
            'Matériaux de toiture' => [
                'Tuiles',
                'Ardoises',
                'Bacs acier',
                'Gouttières',
                'Isolation toiture',
            ],
            'Quincaillerie' => [
                'Visserie',
                'Boulonnerie',
                'Charnières',
                'Serrurerie',
                'Fixations',
            ],
            'Jardinage et espaces verts' => [
                'Outillage jardin',
                'Arrosage',
                'Clôtures',
                'Mobilier extérieur',
                'Éclairage extérieur',
            ],
        ];

        foreach ($categoriesPrincipales as $nomCategoriePrincipale => $sousCategories) {
            // Créer la catégorie principale
            $categoriePrincipale = Category::create([
                'name' => $nomCategoriePrincipale,
                'category_id' => null,
            ]);

            // Créer les sous-catégories
            foreach ($sousCategories as $nomSousCategorie) {
                Category::create([
                    'name' => $nomSousCategorie,
                    'category_id' => $categoriePrincipale->id,
                ]);
            }
        }

        // Créer quelques catégories supplémentaires avec la factory pour la diversité
        Category::factory()->count(5)->principale()->create();
        
        // Créer des sous-catégories aléatoires pour certaines catégories existantes
        $categoriesExistantes = Category::whereNull('category_id')->get();
        foreach ($categoriesExistantes->take(3) as $categorie) {
            Category::factory()->count(rand(2, 4))->sousCategorie($categorie->id)->create();
        }

        $this->command->info('Categories créées avec succès !');
        $this->command->info('Total catégories principales : ' . Category::whereNull('category_id')->count());
        $this->command->info('Total sous-catégories : ' . Category::whereNotNull('category_id')->count());
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\Category;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer seulement quelques catégories principales pour les tests
        $categoriesPrincipales = [
            'Gros œuvre' => [
                'Béton et mortier',
                'Maçonnerie',
            ],
            'Plomberie' => [
                'Tubes et raccords',
                'Sanitaires',
            ],
            'Électricité' => [
                'Câbles et fils',
                'Éclairage',
            ],
            'Outillage' => [
                'Outillage électroportatif',
                'Outillage à main',
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

        // Créer seulement 2 catégories supplémentaires avec la factory
        Category::factory()->count(2)->principale()->create();

        // Créer quelques sous-catégories aléatoires pour 2 catégories existantes
        $categoriesExistantes = Category::whereNull('category_id')->get();
        foreach ($categoriesExistantes->take(2) as $categorie) {
            Category::factory()->count(rand(1, 2))->sousCategorie($categorie->id)->create();
        }

        $this->command->info('Categories créées avec succès !');
        $this->command->info('Total catégories principales : '.Category::whereNull('category_id')->count());
        $this->command->info('Total sous-catégories : '.Category::whereNotNull('category_id')->count());
    }
}

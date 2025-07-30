<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\CategoryProduit;
use Illuminate\Database\Seeder;

final class CategoryProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏷️  Création des catégories de produits...');

        if (CategoryProduit::count() > 0) {
            $this->command->warn('⚠️  Les catégories de produits existent déjà, passage...');
            return;
        }

        // Catégories racines pour le BTP
        $categoriesRacines = [
            [
                'code' => 'MAT',
                'nom' => 'Matériaux',
                'description' => 'Matériaux de construction',
                'couleur' => '#3B82F6',
                'ordre' => 1,
            ],
            [
                'code' => 'OUT',
                'nom' => 'Outillage',
                'description' => 'Outils et équipements',
                'couleur' => '#EF4444',
                'ordre' => 2,
            ],
            [
                'code' => 'SER',
                'nom' => 'Services',
                'description' => 'Prestations de services',
                'couleur' => '#10B981',
                'ordre' => 3,
            ],
            [
                'code' => 'LOC',
                'nom' => 'Location',
                'description' => 'Location d\'équipements',
                'couleur' => '#F59E0B',
                'ordre' => 4,
            ],
        ];

        foreach ($categoriesRacines as $categorieData) {
            $categorie = CategoryProduit::factory()->racine()->create($categorieData);
            $this->createSousCategories($categorie);
        }

        $this->command->info('✓ Catégories de produits créées avec succès');
    }

    /**
     * Créer les sous-catégories pour une catégorie donnée
     */
    private function createSousCategories(CategoryProduit $parent): void
    {
        $sousCategories = $this->getSousCategoriesPour($parent->code);

        foreach ($sousCategories as $index => $sousCategorieData) {
            $sousCategorie = CategoryProduit::factory()->enfant()->create([
                'parent_id' => $parent->id,
                'code' => $parent->code . '-' . $sousCategorieData['code'],
                'nom' => $sousCategorieData['nom'],
                'description' => $sousCategorieData['description'],
                'couleur' => $sousCategorieData['couleur'] ?? $parent->couleur,
                'ordre' => $index + 1,
            ]);

            // Créer des sous-sous-catégories pour certaines catégories
            if (isset($sousCategorieData['enfants'])) {
                foreach ($sousCategorieData['enfants'] as $enfantIndex => $enfantData) {
                    CategoryProduit::factory()->enfant()->create([
                        'parent_id' => $sousCategorie->id,
                        'code' => $sousCategorie->code . '-' . $enfantData['code'],
                        'nom' => $enfantData['nom'],
                        'description' => $enfantData['description'],
                        'couleur' => $enfantData['couleur'] ?? $sousCategorie->couleur,
                        'ordre' => $enfantIndex + 1,
                    ]);
                }
            }
        }
    }

    /**
     * Obtenir les sous-catégories selon le code parent
     */
    private function getSousCategoriesPour(string $codeParent): array
    {
        return match ($codeParent) {
            'MAT' => [
                [
                    'code' => 'GRO',
                    'nom' => 'Gros œuvre',
                    'description' => 'Matériaux pour gros œuvre',
                    'couleur' => '#6366F1',
                    'enfants' => [
                        ['code' => 'BET', 'nom' => 'Béton', 'description' => 'Béton et dérivés'],
                        ['code' => 'CIM', 'nom' => 'Ciment', 'description' => 'Ciments et mortiers'],
                        ['code' => 'AGR', 'nom' => 'Agrégats', 'description' => 'Sable, gravier, etc.'],
                    ],
                ],
                [
                    'code' => 'SEC',
                    'nom' => 'Second œuvre',
                    'description' => 'Matériaux pour second œuvre',
                    'couleur' => '#8B5CF6',
                    'enfants' => [
                        ['code' => 'PLA', 'nom' => 'Plâtrerie', 'description' => 'Plâtre et cloisons'],
                        ['code' => 'PEI', 'nom' => 'Peinture', 'description' => 'Peintures et enduits'],
                        ['code' => 'REV', 'nom' => 'Revêtements', 'description' => 'Carrelage, parquet, etc.'],
                    ],
                ],
                [
                    'code' => 'ISO',
                    'nom' => 'Isolation',
                    'description' => 'Matériaux d\'isolation',
                    'couleur' => '#06B6D4',
                ],
                [
                    'code' => 'COU',
                    'nom' => 'Couverture',
                    'description' => 'Matériaux de couverture',
                    'couleur' => '#84CC16',
                ],
            ],
            'OUT' => [
                [
                    'code' => 'ELE',
                    'nom' => 'Électroportatif',
                    'description' => 'Outils électroportatifs',
                    'couleur' => '#F97316',
                ],
                [
                    'code' => 'MAN',
                    'nom' => 'Outillage manuel',
                    'description' => 'Outils manuels',
                    'couleur' => '#EF4444',
                ],
                [
                    'code' => 'MES',
                    'nom' => 'Mesure',
                    'description' => 'Instruments de mesure',
                    'couleur' => '#8B5CF6',
                ],
                [
                    'code' => 'SEC',
                    'nom' => 'Sécurité',
                    'description' => 'Équipements de sécurité',
                    'couleur' => '#DC2626',
                ],
            ],
            'SER' => [
                [
                    'code' => 'ETU',
                    'nom' => 'Études',
                    'description' => 'Services d\'études et conception',
                    'couleur' => '#3B82F6',
                ],
                [
                    'code' => 'REA',
                    'nom' => 'Réalisation',
                    'description' => 'Services de réalisation',
                    'couleur' => '#10B981',
                ],
                [
                    'code' => 'MAI',
                    'nom' => 'Maintenance',
                    'description' => 'Services de maintenance',
                    'couleur' => '#F59E0B',
                ],
                [
                    'code' => 'FOR',
                    'nom' => 'Formation',
                    'description' => 'Services de formation',
                    'couleur' => '#8B5CF6',
                ],
            ],
            'LOC' => [
                [
                    'code' => 'ENG',
                    'nom' => 'Engins',
                    'description' => 'Location d\'engins de chantier',
                    'couleur' => '#F59E0B',
                ],
                [
                    'code' => 'ECH',
                    'nom' => 'Échafaudages',
                    'description' => 'Location d\'échafaudages',
                    'couleur' => '#6B7280',
                ],
                [
                    'code' => 'OUT',
                    'nom' => 'Outillage',
                    'description' => 'Location d\'outillage',
                    'couleur' => '#EF4444',
                ],
                [
                    'code' => 'BEN',
                    'nom' => 'Bennes',
                    'description' => 'Location de bennes',
                    'couleur' => '#059669',
                ],
            ],
            default => [],
        };
    }
}
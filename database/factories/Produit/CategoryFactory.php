<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\Category>
 */
final class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                // Catégories principales de matériaux de construction
                'Gros œuvre',
                'Second œuvre',
                'Finitions',
                'Isolation',
                'Plomberie',
                'Électricité',
                'Chauffage',
                'Menuiserie',
                'Carrelage',
                'Peinture',
                'Outillage',
                'Équipements de sécurité',
                'Matériaux de toiture',
                'Béton et mortier',
                'Métallerie',
                'Cloisons',
                'Revêtements de sol',
                'Sanitaires',
                'Quincaillerie',
                'Jardinage et espaces verts',
            ]),
            'category_id' => null, // Par défaut, catégorie principale
        ];
    }

    /**
     * Catégorie principale (sans parent)
     */
    public function principale(): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => null,
        ]);
    }

    /**
     * Sous-catégorie (avec parent)
     */
    public function sousCategorie(?int $parentId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $parentId ?? Category::factory()->principale(),
            'name' => $this->faker->randomElement([
                // Sous-catégories spécifiques
                'Ciment',
                'Sable',
                'Graviers',
                'Briques',
                'Parpaings',
                'Poutrelles',
                'Ferraillage',
                'Tubes PVC',
                'Raccords',
                'Robinetterie',
                'Câbles électriques',
                'Prises et interrupteurs',
                'Tableaux électriques',
                'Radiateurs',
                'Chaudières',
                'Portes',
                'Fenêtres',
                'Volets',
                'Carreaux',
                'Faïence',
                'Peinture intérieure',
                'Peinture extérieure',
                'Enduits',
                'Perceuses',
                'Scies',
                'Marteaux',
                'Casques',
                'Gants',
                'Chaussures de sécurité',
                'Tuiles',
                'Ardoises',
                'Gouttières',
                'Plaques de plâtre',
                'Rails et montants',
                'Parquet',
                'Lino',
                'Moquette',
                'Lavabos',
                'WC',
                'Baignoires',
                'Vis',
                'Chevilles',
                'Clous',
            ]),
        ]);
    }

    /**
     * Catégorie pour matériaux
     */
    public function materiaux(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Matériaux de construction',
                'Gros œuvre',
                'Second œuvre',
                'Isolation thermique',
                'Isolation phonique',
                'Étanchéité',
            ]),
        ]);
    }

    /**
     * Catégorie pour outillage
     */
    public function outillage(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Outillage électroportatif',
                'Outillage à main',
                'Machines de chantier',
                'Équipements de mesure',
                'Accessoires d\'outillage',
            ]),
        ]);
    }

    /**
     * Catégorie pour services
     */
    public function services(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Services de construction',
                'Maçonnerie',
                'Plomberie',
                'Électricité',
                'Peinture',
                'Carrelage',
                'Menuiserie',
                'Couverture',
                'Terrassement',
                'Nettoyage',
            ]),
        ]);
    }

    /**
     * Catégorie pour location
     */
    public function location(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Location d\'équipements',
                'Location d\'engins',
                'Location d\'échafaudages',
                'Location d\'outillage',
                'Location de véhicules',
            ]),
        ]);
    }
}

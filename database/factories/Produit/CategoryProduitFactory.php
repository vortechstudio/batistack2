<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\CategoryProduit;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CategoryProduitFactory extends Factory
{
    protected $model = CategoryProduit::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('CAT???')),
            'nom' => $this->faker->words(2, true),
            'description' => $this->faker->optional(0.7)->sentence(),
            'couleur' => $this->faker->hexColor(),
            'actif' => $this->faker->boolean(90),
            'ordre' => $this->faker->numberBetween(1, 100),
            'parent_id' => null, // Sera défini dans les seeders pour créer la hiérarchie
            'metadata' => $this->faker->optional(0.3)->randomElements([
                'icon' => $this->faker->randomElement(['cube', 'wrench', 'hammer', 'paint-brush']),
                'tags' => $this->faker->words(3),
            ]),
        ];
    }

    /**
     * Catégorie racine (sans parent)
     */
    public function racine(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => null,
            'ordre' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Catégorie enfant
     */
    public function enfant(?CategoryProduit $parent = null): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent?->id ?? CategoryProduit::factory()->racine(),
            'ordre' => $this->faker->numberBetween(1, 50),
        ]);
    }

    /**
     * Catégorie active
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => true,
        ]);
    }

    /**
     * Catégorie inactive
     */
    public function inactif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }
}
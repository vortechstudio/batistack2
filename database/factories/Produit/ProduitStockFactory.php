<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\ProduitStock>
 */
final class ProduitStockFactory extends Factory
{
    protected $model = ProduitStock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantite' => $this->faker->numberBetween(0, 500),
            'produit_id' => Produit::factory(),
            'entrepot_id' => Entrepot::factory(),
        ];
    }

    /**
     * Stock avec quantité élevée
     */
    public function stockEleve(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(100, 1000),
        ]);
    }

    /**
     * Stock faible
     */
    public function stockFaible(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Stock en rupture
     */
    public function enRupture(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => 0,
        ]);
    }

    /**
     * Stock pour un produit spécifique
     */
    public function pourProduit(Produit $produit): static
    {
        return $this->state(fn (array $attributes) => [
            'produit_id' => $produit->id,
        ]);
    }

    /**
     * Stock pour un entrepôt spécifique
     */
    public function pourEntrepot(Entrepot $entrepot): static
    {
        return $this->state(fn (array $attributes) => [
            'entrepot_id' => $entrepot->id,
        ]);
    }

    /**
     * Stock avec quantité normale
     */
    public function stockNormal(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(20, 100),
        ]);
    }

    /**
     * Stock critique (en dessous du seuil)
     */
    public function stockCritique(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(1, 5),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Produit;
use App\Models\Produit\TarifFournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\TarifFournisseur>
 */
final class TarifFournisseurFactory extends Factory
{
    protected $model = TarifFournisseur::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ref_fournisseur' => $this->generateRefFournisseur(),
            'qte_minimal' => $this->faker->randomFloat(2, 1, 100),
            'prix_unitaire' => $this->faker->randomFloat(2, 0.50, 500),
            'delai_livraison' => $this->faker->numberBetween(1, 30),
            'barrecode' => $this->faker->optional(0.7)->ean13(),
            'produit_id' => Produit::factory(),
        ];
    }

    /**
     * Tarif pour matériaux de construction
     */
    public function materiauConstruction(): static
    {
        return $this->state(fn (array $attributes) => [
            'qte_minimal' => $this->faker->randomFloat(2, 10, 500),
            'prix_unitaire' => $this->faker->randomFloat(2, 5, 200),
            'delai_livraison' => $this->faker->numberBetween(3, 15),
        ]);
    }

    /**
     * Tarif pour outillage
     */
    public function outillage(): static
    {
        return $this->state(fn (array $attributes) => [
            'qte_minimal' => $this->faker->randomFloat(2, 1, 5),
            'prix_unitaire' => $this->faker->randomFloat(2, 20, 1000),
            'delai_livraison' => $this->faker->numberBetween(1, 7),
            'barrecode' => $this->faker->ean13(),
        ]);
    }

    /**
     * Tarif avec livraison rapide
     */
    public function livraisonRapide(): static
    {
        return $this->state(fn (array $attributes) => [
            'delai_livraison' => $this->faker->numberBetween(1, 3),
            'prix_unitaire' => $attributes['prix_unitaire'] * 1.1, // Majoration pour livraison rapide
        ]);
    }

    /**
     * Tarif avec quantité minimale élevée
     */
    public function quantiteMinimaleElevee(): static
    {
        return $this->state(fn (array $attributes) => [
            'qte_minimal' => $this->faker->randomFloat(2, 100, 1000),
            'prix_unitaire' => $attributes['prix_unitaire'] * 0.85, // Réduction pour gros volume
        ]);
    }

    /**
     * Tarif économique
     */
    public function economique(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 0.10, 10),
            'qte_minimal' => $this->faker->randomFloat(2, 50, 500),
            'delai_livraison' => $this->faker->numberBetween(7, 21),
        ]);
    }

    /**
     * Tarif premium
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 100, 2000),
            'qte_minimal' => $this->faker->randomFloat(2, 1, 10),
            'delai_livraison' => $this->faker->numberBetween(1, 5),
            'barrecode' => $this->faker->ean13(),
        ]);
    }

    /**
     * Tarif pour un produit spécifique
     */
    public function pourProduit(int $produitId): static
    {
        return $this->state(fn (array $attributes) => [
            'produit_id' => $produitId,
        ]);
    }

    /**
     * Tarif avec code-barres obligatoire
     */
    public function avecCodeBarre(): static
    {
        return $this->state(fn (array $attributes) => [
            'barrecode' => $this->faker->ean13(),
        ]);
    }

    /**
     * Tarif sans code-barres
     */
    public function sansCodeBarre(): static
    {
        return $this->state(fn (array $attributes) => [
            'barrecode' => null,
        ]);
    }

    /**
     * Génère une référence fournisseur unique
     */
    private function generateRefFournisseur(): string
    {
        $prefixes = ['REF', 'ART', 'SKU', 'PROD', 'MAT'];
        $prefix = $this->faker->randomElement($prefixes);
        $number = mb_str_pad($this->faker->unique()->numberBetween(1, 99999999), 8, '0', STR_PAD_LEFT);

        return $prefix.'-'.$number;
    }
}

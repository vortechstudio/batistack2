<?php

namespace Database\Factories\Produit;

use App\Enums\Produits\TauxTVA;
use App\Models\Produit\Produit;
use App\Models\Produit\Service;
use App\Models\Produit\TarifClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\TarifClient>
 */
class TarifClientFactory extends Factory
{
    protected $model = TarifClient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Décider aléatoirement si c'est pour un produit ou un service
        $isProduit = $this->faker->boolean(70); // 70% produits, 30% services

        return [
            'prix_unitaire' => $this->faker->randomFloat(2, 1, 1000),
            'taux_tva' => $this->faker->randomElement(TauxTVA::values()),
            'produit_id' => $isProduit ? Produit::factory() : null,
            'service_id' => !$isProduit ? Service::factory() : null,
        ];
    }

    /**
     * Tarif pour produit uniquement
     */
    public function pourProduit(?int $produitId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'produit_id' => $produitId ?? Produit::factory(),
            'service_id' => null,
        ]);
    }

    /**
     * Tarif pour service uniquement
     */
    public function pourService(?int $serviceId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'service_id' => $serviceId ?? Service::factory(),
            'produit_id' => null,
        ]);
    }

    /**
     * Tarif avec TVA normale (20%)
     */
    public function tvaNormale(): static
    {
        return $this->state(fn (array $attributes) => [
            'taux_tva' => TauxTVA::NORMAL->value,
        ]);
    }

    /**
     * Tarif avec TVA réduite (5,5% - rénovation énergétique)
     */
    public function tvaReduite(): static
    {
        return $this->state(fn (array $attributes) => [
            'taux_tva' => TauxTVA::REDUIT_5_5->value,
        ]);
    }

    /**
     * Tarif avec TVA intermédiaire (10% - travaux d'amélioration)
     */
    public function tvaIntermediaire(): static
    {
        return $this->state(fn (array $attributes) => [
            'taux_tva' => TauxTVA::INTERMEDIAIRE->value,
        ]);
    }

    /**
     * Tarif exonéré de TVA
     */
    public function tvaZero(): static
    {
        return $this->state(fn (array $attributes) => [
            'taux_tva' => TauxTVA::ZERO->value,
        ]);
    }

    /**
     * Tarif économique
     */
    public function economique(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 0.50, 50),
        ]);
    }

    /**
     * Tarif standard
     */
    public function standard(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 10, 200),
            'taux_tva' => TauxTVA::NORMAL->value,
        ]);
    }

    /**
     * Tarif premium
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 200, 2000),
            'taux_tva' => TauxTVA::NORMAL->value,
        ]);
    }

    /**
     * Tarif pour matériaux de construction
     */
    public function materiauConstruction(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 5, 300),
            'taux_tva' => TauxTVA::NORMAL->value,
        ]);
    }

    /**
     * Tarif pour services de rénovation (TVA réduite)
     */
    public function serviceRenovation(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 50, 500),
            'taux_tva' => TauxTVA::REDUIT_5_5->value,
            'produit_id' => null,
            'service_id' => Service::factory(),
        ]);
    }

    /**
     * Tarif pour services d'amélioration (TVA intermédiaire)
     */
    public function serviceAmelioration(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $this->faker->randomFloat(2, 30, 300),
            'taux_tva' => TauxTVA::INTERMEDIAIRE->value,
            'produit_id' => null,
            'service_id' => Service::factory(),
        ]);
    }

    /**
     * Tarif avec un taux de TVA spécifique
     */
    public function avecTauxTVA(float $taux): static
    {
        return $this->state(fn (array $attributes) => [
            'taux_tva' => $taux,
        ]);
    }
}

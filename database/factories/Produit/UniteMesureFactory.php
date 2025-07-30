<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\UniteMesure;
use Illuminate\Database\Eloquent\Factories\Factory;

final class UniteMesureFactory extends Factory
{
    protected $model = UniteMesure::class;

    public function definition(): array
    {
        $unites = [
            ['code' => 'M', 'nom' => 'Mètre', 'symbole' => 'm', 'type' => 'longueur'],
            ['code' => 'CM', 'nom' => 'Centimètre', 'symbole' => 'cm', 'type' => 'longueur'],
            ['code' => 'MM', 'nom' => 'Millimètre', 'symbole' => 'mm', 'type' => 'longueur'],
            ['code' => 'KG', 'nom' => 'Kilogramme', 'symbole' => 'kg', 'type' => 'poids'],
            ['code' => 'G', 'nom' => 'Gramme', 'symbole' => 'g', 'type' => 'poids'],
            ['code' => 'L', 'nom' => 'Litre', 'symbole' => 'l', 'type' => 'volume'],
            ['code' => 'ML', 'nom' => 'Millilitre', 'symbole' => 'ml', 'type' => 'volume'],
            ['code' => 'M2', 'nom' => 'Mètre carré', 'symbole' => 'm²', 'type' => 'surface'],
            ['code' => 'M3', 'nom' => 'Mètre cube', 'symbole' => 'm³', 'type' => 'volume'],
            ['code' => 'U', 'nom' => 'Unité', 'symbole' => 'u', 'type' => 'quantite'],
            ['code' => 'H', 'nom' => 'Heure', 'symbole' => 'h', 'type' => 'temps'],
            ['code' => 'J', 'nom' => 'Jour', 'symbole' => 'j', 'type' => 'temps'],
        ];

        $unite = $this->faker->randomElement($unites);

        return [
            'code' => $unite['code'],
            'nom' => $unite['nom'],
            'symbole' => $unite['symbole'],
            'type' => $unite['type'],
            'description' => $this->faker->optional(0.5)->sentence(),
            'actif' => $this->faker->boolean(95),
            'facteur_conversion' => $this->getFacteurConversion($unite['code']),
            'unite_base_id' => $this->getUniteBaseId($unite['code']),
            'metadata' => $this->faker->optional(0.2)->randomElements([
                'precision' => $this->faker->numberBetween(0, 3),
                'format' => $this->faker->randomElement(['decimal', 'entier']),
            ]),
        ];
    }

    /**
     * Unité de base (facteur de conversion = 1)
     */
    public function base(): static
    {
        return $this->state(fn (array $attributes) => [
            'facteur_conversion' => 1.0,
            'unite_base_id' => null,
        ]);
    }

    /**
     * Unité dérivée
     */
    public function derivee(?UniteMesure $uniteBase = null): static
    {
        return $this->state(fn (array $attributes) => [
            'unite_base_id' => $uniteBase?->id ?? UniteMesure::factory()->base(),
            'facteur_conversion' => $this->faker->randomFloat(4, 0.001, 1000),
        ]);
    }

    /**
     * Unité active
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => true,
        ]);
    }

    /**
     * Unité par type
     */
    public function parType(string $type): static
    {
        $unitesParType = [
            'longueur' => [
                ['code' => 'M', 'nom' => 'Mètre', 'symbole' => 'm', 'facteur' => 1.0],
                ['code' => 'CM', 'nom' => 'Centimètre', 'symbole' => 'cm', 'facteur' => 0.01],
                ['code' => 'MM', 'nom' => 'Millimètre', 'symbole' => 'mm', 'facteur' => 0.001],
            ],
            'poids' => [
                ['code' => 'KG', 'nom' => 'Kilogramme', 'symbole' => 'kg', 'facteur' => 1.0],
                ['code' => 'G', 'nom' => 'Gramme', 'symbole' => 'g', 'facteur' => 0.001],
            ],
            'volume' => [
                ['code' => 'L', 'nom' => 'Litre', 'symbole' => 'l', 'facteur' => 1.0],
                ['code' => 'ML', 'nom' => 'Millilitre', 'symbole' => 'ml', 'facteur' => 0.001],
            ],
        ];

        $unite = $this->faker->randomElement($unitesParType[$type] ?? []);

        return $this->state(fn (array $attributes) => [
            'type' => $type,
            'code' => $unite['code'] ?? strtoupper($this->faker->lexify('???')),
            'nom' => $unite['nom'] ?? $this->faker->word(),
            'symbole' => $unite['symbole'] ?? $this->faker->lexify('??'),
            'facteur_conversion' => $unite['facteur'] ?? 1.0,
        ]);
    }

    private function getFacteurConversion(string $code): float
    {
        return match ($code) {
            'M' => 1.0,
            'CM' => 0.01,
            'MM' => 0.001,
            'KG' => 1.0,
            'G' => 0.001,
            'L' => 1.0,
            'ML' => 0.001,
            'H' => 1.0,
            'J' => 24.0,
            default => 1.0,
        };
    }

    private function getUniteBaseId(string $code): ?int
    {
        // Dans un vrai seeder, on récupérerait l'ID de l'unité de base
        // Ici on retourne null pour les unités de base
        return match ($code) {
            'M', 'KG', 'L', 'M2', 'M3', 'U', 'H' => null,
            default => null, // Sera défini dans les seeders
        };
    }
}
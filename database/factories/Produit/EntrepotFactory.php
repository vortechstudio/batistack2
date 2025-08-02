<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Entrepot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\Entrepot>
 */
final class EntrepotFactory extends Factory
{
    protected $model = Entrepot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Entrepôt Central Paris',
                'Dépôt Lyon Sud',
                'Plateforme Marseille',
                'Centre Logistique Lille',
                'Entrepôt Bordeaux Ouest',
                'Dépôt Toulouse Nord',
                'Plateforme Nantes',
                'Centre de Distribution Strasbourg',
                'Entrepôt Rennes',
                'Dépôt Nice Côte d\'Azur',
                'Plateforme Montpellier',
                'Centre Logistique Clermont-Ferrand',
                'Entrepôt Dijon',
                'Dépôt Metz',
                'Plateforme Orléans',
                'Centre de Stockage Rouen',
                'Entrepôt Le Havre',
                'Dépôt Caen',
                'Plateforme Tours',
                'Centre Logistique Angers',
            ]),
            'description' => $this->faker->optional(0.8)->paragraph(2),
            'address' => $this->faker->optional(0.9)->streetAddress(),
            'code_postal' => $this->faker->optional(0.9)->postcode(),
            'ville' => $this->faker->optional(0.9)->city(),
            'status' => $this->faker->boolean(85), // 85% de chance d'être actif
        ];
    }

    /**
     * Entrepôt actif
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Entrepôt inactif
     */
    public function inactif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Entrepôt principal avec informations complètes
     */
    public function principal(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Entrepôt Principal '.$this->faker->city(),
            'description' => 'Entrepôt principal de stockage et distribution des matériaux de construction. Capacité de stockage importante avec zones spécialisées pour différents types de produits.',
            'address' => $this->faker->streetAddress(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city(),
            'status' => true,
        ]);
    }

    /**
     * Entrepôt régional
     */
    public function regional(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Dépôt Régional '.$this->faker->city(),
            'description' => 'Entrepôt régional pour la distribution locale des produits les plus demandés.',
            'status' => true,
        ]);
    }

    /**
     * Entrepôt spécialisé
     */
    public function specialise(): static
    {
        $specialites = [
            'Matériaux lourds (béton, parpaings, briques)',
            'Outillage et équipements électroportatifs',
            'Plomberie et chauffage',
            'Électricité et domotique',
            'Menuiserie et isolation',
            'Peinture et finitions',
        ];

        return $this->state(fn (array $attributes) => [
            'name' => 'Entrepôt Spécialisé '.$this->faker->city(),
            'description' => 'Entrepôt spécialisé dans : '.$this->faker->randomElement($specialites),
            'status' => true,
        ]);
    }

    /**
     * Entrepôt avec adresse complète
     */
    public function avecAdresseComplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'address' => $this->faker->streetAddress(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city(),
        ]);
    }
}

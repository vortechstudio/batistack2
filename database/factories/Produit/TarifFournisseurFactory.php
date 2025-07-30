<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Produit;
use App\Models\Produit\TarifFournisseur;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TarifFournisseurFactory extends Factory
{
    protected $model = TarifFournisseur::class;

    public function definition(): array
    {
        $prixAchat = $this->faker->randomFloat(2, 5, 1000);
        $estService = $this->faker->boolean(40);

        return [
            'produit_id' => Produit::factory(),
            'tiers_id' => Tiers::factory(),
            'reference_fournisseur' => $this->faker->optional(0.7)->bothify('REF-####-???'),
            'actif' => $this->faker->boolean(90),
            'prefere' => $this->faker->boolean(20),
            'prix_achat' => $prixAchat,
            'devise' => $this->faker->randomElement(['EUR', 'USD']),
            'quantite_minimale' => $this->faker->optional(0.6)->randomFloat(2, 1, 100),
            
            // Remises par quantité
            'seuil_quantite_1' => $this->faker->optional(0.5)->randomFloat(2, 10, 50),
            'remise_quantite_1' => $this->faker->optional(0.5)->randomFloat(2, 2, 10),
            'seuil_quantite_2' => $this->faker->optional(0.3)->randomFloat(2, 50, 100),
            'remise_quantite_2' => $this->faker->optional(0.3)->randomFloat(2, 5, 15),
            'seuil_quantite_3' => $this->faker->optional(0.2)->randomFloat(2, 100, 500),
            'remise_quantite_3' => $this->faker->optional(0.2)->randomFloat(2, 10, 25),
            
            'remise_generale' => $this->faker->optional(0.4)->randomFloat(2, 1, 15),
            'delai_livraison' => $this->faker->optional(0.8)->numberBetween(1, 30),
            'conditions_paiement' => $this->faker->optional(0.7)->randomElement([
                '30 jours net',
                '60 jours net',
                'Comptant',
                '30 jours fin de mois',
                '45 jours net',
            ]),
            
            // Frais de port
            'frais_port_fixe' => $this->faker->optional(0.4)->randomFloat(2, 5, 50),
            'frais_port_pourcentage' => $this->faker->optional(0.3)->randomFloat(2, 1, 10),
            'franco_port' => $this->faker->optional(0.5)->randomFloat(2, 100, 1000),
            
            'date_debut' => $this->faker->optional(0.8)->dateTimeBetween('-6 months', 'now'),
            'date_fin' => $this->faker->optional(0.3)->dateTimeBetween('now', '+1 year'),
            'statut' => $this->faker->randomElement(['brouillon', 'valide', 'expire', 'suspendu']),
            'priorite' => $this->faker->numberBetween(1, 10),
            
            // Champs spécifiques aux services
            'tarif_horaire' => $estService ? $this->faker->randomFloat(2, 25, 150) : null,
            'cout_deplacement' => $estService ? $this->faker->optional(0.6)->randomFloat(2, 10, 100) : null,
            'majoration_weekend' => $estService ? $this->faker->optional(0.4)->randomFloat(2, 10, 50) : null,
            'majoration_nuit' => $estService ? $this->faker->optional(0.3)->randomFloat(2, 15, 75) : null,
            'majoration_urgence' => $estService ? $this->faker->optional(0.3)->randomFloat(2, 20, 100) : null,
            'grille_tarifaire' => $estService ? $this->faker->optional(0.3)->randomElements([
                'normal' => $this->faker->randomFloat(2, 40, 80),
                'weekend' => $this->faker->randomFloat(2, 50, 100),
                'nuit' => $this->faker->randomFloat(2, 60, 120),
                'urgence' => $this->faker->randomFloat(2, 80, 150),
            ]) : null,
            
            'notes' => $this->faker->optional(0.3)->sentence(),
            'metadata' => $this->faker->optional(0.2)->randomElements([
                'contact_commercial' => $this->faker->name(),
                'telephone' => $this->faker->phoneNumber(),
                'email' => $this->faker->email(),
            ]),
        ];
    }

    /**
     * Tarif actif
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => true,
            'statut' => 'valide',
        ]);
    }

    /**
     * Tarif préféré
     */
    public function prefere(): static
    {
        return $this->state(fn (array $attributes) => [
            'prefere' => true,
            'actif' => true,
            'priorite' => 1,
        ]);
    }

    /**
     * Tarif avec remises
     */
    public function avecRemises(): static
    {
        return $this->state(fn (array $attributes) => [
            'seuil_quantite_1' => 10,
            'remise_quantite_1' => 5,
            'seuil_quantite_2' => 50,
            'remise_quantite_2' => 10,
            'seuil_quantite_3' => 100,
            'remise_quantite_3' => 15,
            'remise_generale' => 3,
        ]);
    }

    /**
     * Tarif pour service
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'tarif_horaire' => $this->faker->randomFloat(2, 30, 120),
            'cout_deplacement' => $this->faker->randomFloat(2, 15, 80),
            'majoration_weekend' => $this->faker->randomFloat(2, 15, 40),
            'majoration_nuit' => $this->faker->randomFloat(2, 20, 60),
            'majoration_urgence' => $this->faker->randomFloat(2, 25, 80),
        ]);
    }

    /**
     * Tarif pour matériel
     */
    public function materiel(): static
    {
        return $this->state(fn (array $attributes) => [
            'tarif_horaire' => null,
            'cout_deplacement' => null,
            'majoration_weekend' => null,
            'majoration_nuit' => null,
            'majoration_urgence' => null,
            'grille_tarifaire' => null,
            'quantite_minimale' => $this->faker->randomFloat(2, 1, 50),
        ]);
    }

    /**
     * Tarif valide (avec dates)
     */
    public function valide(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subMonths(2),
            'date_fin' => now()->addMonths(6),
            'statut' => 'valide',
            'actif' => true,
        ]);
    }

    /**
     * Tarif expiré
     */
    public function expire(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subMonths(6),
            'date_fin' => now()->subDays(10),
            'statut' => 'expire',
            'actif' => false,
        ]);
    }
}
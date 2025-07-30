<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Produit;
use App\Models\Produit\TarifClient;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TarifClientFactory extends Factory
{
    protected $model = TarifClient::class;

    public function definition(): array
    {
        $prixVente = $this->faker->randomFloat(2, 10, 2000);
        $estService = $this->faker->boolean(40);
        $typeClient = $this->faker->randomElement(['particulier', 'professionnel', 'entreprise']);

        return [
            'produit_id' => Produit::factory(),
            'tiers_id' => $this->faker->optional(0.7)->randomElement(Tiers::pluck('id')->toArray()),
            'type_client' => $typeClient,
            'prix_vente' => $prixVente,
            'devise' => $this->faker->randomElement(['EUR', 'USD']),
            'marge_pourcentage' => $this->faker->randomFloat(2, 10, 50),
            
            // Tarifs dégressifs
            'prix_quantite_1' => $this->faker->optional(0.6)->randomFloat(2, $prixVente * 0.9, $prixVente * 0.95),
            'seuil_quantite_1' => $this->faker->optional(0.6)->randomFloat(2, 5, 25),
            'prix_quantite_2' => $this->faker->optional(0.4)->randomFloat(2, $prixVente * 0.8, $prixVente * 0.9),
            'seuil_quantite_2' => $this->faker->optional(0.4)->randomFloat(2, 25, 100),
            'prix_quantite_3' => $this->faker->optional(0.2)->randomFloat(2, $prixVente * 0.7, $prixVente * 0.8),
            'seuil_quantite_3' => $this->faker->optional(0.2)->randomFloat(2, 100, 500),
            
            // Remises commerciales
            'remise_commerciale' => $this->faker->optional(0.3)->randomFloat(2, 1, 20),
            'remise_fidelite' => $this->faker->optional(0.2)->randomFloat(2, 1, 15),
            'remise_volume' => $this->faker->optional(0.2)->randomFloat(2, 1, 25),
            'seuil_remise_volume' => $this->faker->optional(0.2)->randomFloat(2, 100, 1000),
            
            'prix_minimum' => $this->faker->optional(0.4)->randomFloat(2, $prixVente * 0.6, $prixVente * 0.8),
            'prix_maximum' => $this->faker->optional(0.3)->randomFloat(2, $prixVente * 1.2, $prixVente * 1.5),
            
            // Conditions commerciales
            'conditions_paiement' => $this->faker->optional(0.8)->randomElement([
                '30 jours net',
                '60 jours net',
                'Comptant',
                '30 jours fin de mois',
                '45 jours net',
                'Acompte 30%',
            ]),
            'delai_livraison' => $this->faker->optional(0.7)->numberBetween(1, 21),
            'frais_livraison' => $this->faker->optional(0.5)->randomFloat(2, 10, 100),
            'seuil_franco_livraison' => $this->faker->optional(0.4)->randomFloat(2, 200, 1500),
            'zone_livraison' => $this->faker->optional(0.3)->city(),
            
            // Validité et statut
            'date_debut' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'date_fin' => $this->faker->optional(0.4)->dateTimeBetween('now', '+1 year'),
            'actif' => $this->faker->boolean(90),
            'tarif_public' => $this->faker->boolean(20),
            'priorite' => $this->faker->numberBetween(0, 10),
            
            // Champs spécifiques aux services
            'tarif_horaire' => $estService ? $this->faker->randomFloat(2, 40, 200) : null,
            'tarif_deplacement' => $estService ? $this->faker->optional(0.6)->randomFloat(2, 20, 150) : null,
            'majoration_urgence' => $estService ? $this->faker->optional(0.3)->randomFloat(2, 25, 120) : null,
            'majoration_weekend' => $estService ? $this->faker->optional(0.4)->randomFloat(2, 15, 60) : null,
            'majoration_nuit' => $estService ? $this->faker->optional(0.3)->randomFloat(2, 20, 80) : null,
            'majoration_ferie' => $estService ? $this->faker->optional(0.3)->randomFloat(2, 25, 100) : null,
            'grille_tarifaire' => $estService ? $this->faker->optional(0.3)->randomElements([
                'normal' => $this->faker->randomFloat(2, 50, 100),
                'weekend' => $this->faker->randomFloat(2, 60, 120),
                'nuit' => $this->faker->randomFloat(2, 70, 140),
                'urgence' => $this->faker->randomFloat(2, 90, 180),
            ]) : null,
            'zones_intervention' => $estService ? $this->faker->optional(0.2)->randomElements([
                'zone_1' => ['nom' => 'Centre-ville', 'tarif' => $this->faker->randomFloat(2, 50, 100)],
                'zone_2' => ['nom' => 'Périphérie', 'tarif' => $this->faker->randomFloat(2, 60, 120)],
            ]) : null,
            
            // Tarifs spéciaux
            'prix_forfait' => $this->faker->optional(0.3)->randomFloat(2, 100, 1000),
            'description_forfait' => $this->faker->optional(0.3)->sentence(),
            
            // Informations complémentaires
            'notes' => $this->faker->optional(0.3)->sentence(),
            'contact_commercial' => $this->faker->optional(0.4)->name(),
            'negociable' => $this->faker->boolean(60),
            'metadata' => $this->faker->optional(0.2)->randomElements([
                'conditions_speciales' => $this->faker->sentence(),
                'zone_geographique' => $this->faker->city(),
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
        ]);
    }

    /**
     * Tarif général (sans client spécifique)
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiers_id' => null,
            'type_client' => 'general',
            'priorite' => 5,
        ]);
    }

    /**
     * Tarif pour particulier
     */
    public function particulier(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_client' => 'particulier',
            'marge_pourcentage' => $this->faker->randomFloat(2, 20, 40),
            'conditions_paiement' => 'Comptant',
        ]);
    }

    /**
     * Tarif pour professionnel
     */
    public function professionnel(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_client' => 'professionnel',
            'marge_pourcentage' => $this->faker->randomFloat(2, 15, 30),
            'conditions_paiement' => '30 jours net',
            'negociable' => true,
        ]);
    }

    /**
     * Tarif pour entreprise
     */
    public function entreprise(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_client' => 'entreprise',
            'marge_pourcentage' => $this->faker->randomFloat(2, 10, 25),
            'conditions_paiement' => '60 jours net',
            'negociable' => true,
        ]);
    }

    /**
     * Tarif avec remises dégressives
     */
    public function avecDegressif(): static
    {
        $prixBase = $this->faker->randomFloat(2, 50, 500);
        
        return $this->state(fn (array $attributes) => [
            'prix_vente' => $prixBase,
            'seuil_quantite_1' => 10,
            'prix_quantite_1' => $prixBase * 0.95,
            'seuil_quantite_2' => 50,
            'prix_quantite_2' => $prixBase * 0.90,
            'seuil_quantite_3' => 100,
            'prix_quantite_3' => $prixBase * 0.85,
        ]);
    }

    /**
     * Tarif pour service
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'tarif_horaire' => $this->faker->randomFloat(2, 50, 150),
            'tarif_deplacement' => $this->faker->randomFloat(2, 25, 100),
            'majoration_weekend' => $this->faker->randomFloat(2, 20, 50),
            'majoration_nuit' => $this->faker->randomFloat(2, 25, 70),
            'majoration_urgence' => $this->faker->randomFloat(2, 30, 100),
            'majoration_ferie' => $this->faker->randomFloat(2, 25, 80),
        ]);
    }

    /**
     * Tarif pour matériel
     */
    public function materiel(): static
    {
        return $this->state(fn (array $attributes) => [
            'tarif_horaire' => null,
            'tarif_deplacement' => null,
            'majoration_weekend' => null,
            'majoration_nuit' => null,
            'majoration_urgence' => null,
            'majoration_ferie' => null,
            'grille_tarifaire' => null,
            'zones_intervention' => null,
        ]);
    }

    /**
     * Tarif négociable
     */
    public function negociable(): static
    {
        return $this->state(fn (array $attributes) => [
            'negociable' => true,
            'prix_minimum' => $attributes['prix_vente'] * 0.7,
            'prix_maximum' => $attributes['prix_vente'] * 1.3,
        ]);
    }

    /**
     * Tarif valide (avec dates)
     */
    public function valide(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subMonth(),
            'date_fin' => now()->addMonths(8),
            'actif' => true,
        ]);
    }

    /**
     * Tarif expiré
     */
    public function expire(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subMonths(8),
            'date_fin' => now()->subWeeks(2),
            'actif' => false,
        ]);
    }
}
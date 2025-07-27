<?php

namespace Database\Factories\RH;

use App\Enums\RH\ModePaiementFrais;
use App\Enums\RH\TypeFrais;
use App\Models\Chantiers\Chantiers;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NoteFraisDetailFactory extends Factory
{
    protected $model = NoteFraisDetail::class;

    public function definition(): array
    {
        $typeFrais = $this->faker->randomElement([
            'transport', 'hebergement', 'restauration', 'carburant', 
            'peage', 'parking', 'materiel', 'formation', 'telecommunication', 'autre'
        ]);
        
        $montantHT = $this->faker->randomFloat(2, 5, 500);
        $tauxTVA = $this->faker->randomElement([0, 5.5, 10, 20]);
        $montantTVA = round($montantHT * $tauxTVA / 100, 2);
        $montantTTC = $montantHT + $montantTVA;
        
        return [
            'note_frais_id' => NoteFrais::factory(),
            'date_frais' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'type_frais' => $typeFrais,
            'libelle' => $this->generateLibelle($typeFrais),
            'montant_ht' => $montantHT,
            'taux_tva' => $tauxTVA,
            'montant_tva' => $montantTVA,
            'montant_ttc' => $montantTTC,
            'montant_valide' => $this->faker->optional(0.3)->randomFloat(2, $montantTTC * 0.8, $montantTTC),
            'fournisseur' => $this->faker->optional(0.7)->company(),
            'numero_facture' => $this->faker->optional(0.6)->regexify('[A-Z]{2}[0-9]{6}'),
            'mode_paiement' => $this->faker->randomElement(['especes', 'carte', 'cheque', 'virement', 'autre']),
            'remboursable' => $this->faker->boolean(90), // 90% remboursable
            'commentaire' => $this->faker->optional(0.3)->sentence(),
            'justificatif_path' => $this->faker->optional(0.7)->filePath(),
            'kilometrage' => $typeFrais === 'transport' ? $this->faker->optional(0.8)->randomFloat(1, 5, 500) : null,
            'lieu_depart' => $typeFrais === 'transport' ? $this->faker->optional(0.8)->city() : null,
            'lieu_arrivee' => $typeFrais === 'transport' ? $this->faker->optional(0.8)->city() : null,
            'chantier_id' => $this->faker->optional(0.4)->randomElement([null, Chantiers::factory()]),
            'metadata' => $this->faker->optional(0.2)->randomElements([
                'urgence' => $this->faker->boolean,
                'mission' => $this->faker->word,
                'client' => $this->faker->company,
            ]),
            'created_at' => Carbon::now()->subDays($this->faker->numberBetween(1, 60)),
            'updated_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
        ];
    }

    /**
     * Frais de transport
     */
    public function transport(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_frais' => 'transport',
            'libelle' => $this->faker->randomElement([
                'Déplacement client',
                'Transport chantier',
                'Mission commerciale',
                'Formation externe',
                'Rendez-vous fournisseur'
            ]),
            'kilometrage' => $this->faker->randomFloat(1, 10, 300),
            'lieu_depart' => $this->faker->city(),
            'lieu_arrivee' => $this->faker->city(),
            'montant_ht' => $this->faker->randomFloat(2, 20, 200),
        ]);
    }

    /**
     * Frais de restauration
     */
    public function restauration(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_frais' => 'restauration',
            'libelle' => $this->faker->randomElement([
                'Repas client',
                'Déjeuner mission',
                'Dîner d\'affaires',
                'Petit-déjeuner réunion',
                'Pause café équipe'
            ]),
            'fournisseur' => $this->faker->randomElement([
                'Restaurant Le Gourmet',
                'Brasserie du Centre',
                'Café de la Gare',
                'Hôtel Restaurant',
                'Quick Service'
            ]),
            'montant_ht' => $this->faker->randomFloat(2, 8, 80),
            'kilometrage' => null,
            'lieu_depart' => null,
            'lieu_arrivee' => null,
        ]);
    }

    /**
     * Frais d'hébergement
     */
    public function hebergement(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_frais' => 'hebergement',
            'libelle' => $this->faker->randomElement([
                'Nuit d\'hôtel mission',
                'Hébergement formation',
                'Hôtel déplacement client',
                'Chambre d\'hôtes',
                'Résidence temporaire'
            ]),
            'fournisseur' => $this->faker->randomElement([
                'Hôtel Ibis',
                'Best Western',
                'Campanile',
                'Novotel',
                'Hôtel du Commerce'
            ]),
            'montant_ht' => $this->faker->randomFloat(2, 60, 250),
            'kilometrage' => null,
            'lieu_depart' => null,
            'lieu_arrivee' => null,
        ]);
    }

    /**
     * Frais de carburant
     */
    public function carburant(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_frais' => 'carburant',
            'libelle' => $this->faker->randomElement([
                'Essence véhicule société',
                'Diesel mission',
                'Carburant déplacement',
                'Plein d\'essence',
                'Gasoil véhicule'
            ]),
            'fournisseur' => $this->faker->randomElement([
                'Total Energies',
                'Shell',
                'BP',
                'Esso',
                'Intermarché'
            ]),
            'montant_ht' => $this->faker->randomFloat(2, 30, 120),
            'kilometrage' => null,
            'lieu_depart' => null,
            'lieu_arrivee' => null,
        ]);
    }

    /**
     * Frais de matériel
     */
    public function materiel(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_frais' => 'materiel',
            'libelle' => $this->faker->randomElement([
                'Fournitures bureau',
                'Matériel informatique',
                'Outillage chantier',
                'Équipement sécurité',
                'Consommables'
            ]),
            'fournisseur' => $this->faker->randomElement([
                'Leroy Merlin',
                'Brico Dépôt',
                'Bureau Vallée',
                'Castorama',
                'Point P'
            ]),
            'montant_ht' => $this->faker->randomFloat(2, 15, 300),
            'kilometrage' => null,
            'lieu_depart' => null,
            'lieu_arrivee' => null,
        ]);
    }

    /**
     * Avec justificatif
     */
    public function avecJustificatif(): static
    {
        return $this->state(fn (array $attributes) => [
            'justificatif_path' => '/storage/justificatifs/' . $this->faker->uuid() . '.pdf',
        ]);
    }

    /**
     * Sans justificatif
     */
    public function sansJustificatif(): static
    {
        return $this->state(fn (array $attributes) => [
            'justificatif_path' => null,
        ]);
    }

    /**
     * Non remboursable
     */
    public function nonRemboursable(): static
    {
        return $this->state(fn (array $attributes) => [
            'remboursable' => false,
            'commentaire' => 'Frais personnel non remboursable',
        ]);
    }

    /**
     * Générer un libellé en fonction du type de frais
     */
    private function generateLibelle(string $typeFrais): string
    {
        $libelles = [
            'transport' => [
                'Déplacement client', 'Transport chantier', 'Mission commerciale',
                'Formation externe', 'Rendez-vous fournisseur', 'Visite technique'
            ],
            'hebergement' => [
                'Nuit d\'hôtel mission', 'Hébergement formation', 'Hôtel déplacement client',
                'Chambre d\'hôtes', 'Résidence temporaire'
            ],
            'restauration' => [
                'Repas client', 'Déjeuner mission', 'Dîner d\'affaires',
                'Petit-déjeuner réunion', 'Pause café équipe'
            ],
            'carburant' => [
                'Essence véhicule société', 'Diesel mission', 'Carburant déplacement',
                'Plein d\'essence', 'Gasoil véhicule'
            ],
            'peage' => [
                'Péage autoroute', 'Frais de route', 'Péage mission',
                'Autoroute déplacement', 'Frais péage client'
            ],
            'parking' => [
                'Parking client', 'Stationnement mission', 'Parking aéroport',
                'Frais parking', 'Stationnement payant'
            ],
            'materiel' => [
                'Fournitures bureau', 'Matériel informatique', 'Outillage chantier',
                'Équipement sécurité', 'Consommables'
            ],
            'formation' => [
                'Formation professionnelle', 'Séminaire', 'Conférence métier',
                'Stage technique', 'Formation sécurité'
            ],
            'telecommunication' => [
                'Frais téléphone', 'Communication client', 'Internet mobile',
                'Forfait téléphonique', 'Frais de communication'
            ],
            'autre' => [
                'Frais divers', 'Dépense exceptionnelle', 'Frais administratifs',
                'Autres frais', 'Dépense non classée'
            ]
        ];

        return $this->faker->randomElement($libelles[$typeFrais] ?? $libelles['autre']);
    }
}
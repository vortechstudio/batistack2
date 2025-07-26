<?php

namespace Database\Factories\RH;

use App\Enums\RH\StatusNoteFrais;
use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NoteFraisFactory extends Factory
{
    protected $model = NoteFrais::class;

    public function definition(): array
    {
        $dateDebut = $this->faker->dateTimeBetween('-3 months', '-1 month');
        $dateFin = (clone $dateDebut)->modify('+' . $this->faker->numberBetween(7, 30) . ' days');
        $dateSoumission = $this->faker->optional(0.8)->dateTimeBetween($dateFin, 'now');
        
        return [
            'numero' => $this->generateNumero(),
            'employe_id' => Employe::factory(),
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'date_soumission' => $dateSoumission,
            'statut' => $this->faker->randomElement(['brouillon', 'soumise', 'validee', 'refusee', 'payee']),
            'motif_refus' => $this->faker->optional(0.1)->sentence(),
            'montant_total' => $this->faker->randomFloat(2, 50, 2000),
            'montant_valide' => $this->faker->optional(0.6)->randomFloat(2, 50, 2000),
            'commentaire_employe' => $this->faker->optional(0.4)->paragraph(),
            'commentaire_validateur' => $this->faker->optional(0.3)->sentence(),
            'validateur_id' => $this->faker->optional(0.6)->randomElement([null, User::factory()]),
            'date_validation' => $this->faker->optional(0.6)->dateTimeBetween($dateSoumission ?? $dateFin, 'now'),
            'date_paiement' => $this->faker->optional(0.4)->dateTimeBetween('-1 month', 'now'),
            'reference_paiement' => $this->faker->optional(0.4)->regexify('VIR[0-9]{8}'),
            'metadata' => $this->faker->optional(0.2)->randomElements([
                'urgente' => $this->faker->boolean,
                'mission' => $this->faker->word,
                'projet' => $this->faker->word,
            ]),
            'created_at' => Carbon::now()->subDays($this->faker->numberBetween(1, 90)),
            'updated_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
        ];
    }

    /**
     * État brouillon
     */
    public function brouillon(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'brouillon',
            'date_soumission' => null,
            'validateur_id' => null,
            'date_validation' => null,
            'date_paiement' => null,
            'reference_paiement' => null,
            'commentaire_validateur' => null,
            'montant_valide' => null,
        ]);
    }

    /**
     * État soumise
     */
    public function soumise(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'soumise',
            'date_soumission' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'validateur_id' => null,
            'date_validation' => null,
            'date_paiement' => null,
            'reference_paiement' => null,
            'commentaire_validateur' => null,
            'montant_valide' => null,
        ]);
    }

    /**
     * État validée
     */
    public function validee(): static
    {
        $dateSoumission = $this->faker->dateTimeBetween('-2 months', '-1 week');
        $dateValidation = $this->faker->dateTimeBetween($dateSoumission, 'now');
        
        return $this->state(fn (array $attributes) => [
            'statut' => 'validee',
            'date_soumission' => $dateSoumission,
            'validateur_id' => User::factory(),
            'date_validation' => $dateValidation,
            'commentaire_validateur' => $this->faker->optional(0.7)->sentence(),
            'montant_valide' => $attributes['montant_total'] ?? $this->faker->randomFloat(2, 50, 2000),
            'date_paiement' => null,
            'reference_paiement' => null,
        ]);
    }

    /**
     * État payée
     */
    public function payee(): static
    {
        $dateSoumission = $this->faker->dateTimeBetween('-3 months', '-1 month');
        $dateValidation = $this->faker->dateTimeBetween($dateSoumission, '-2 weeks');
        $datePaiement = $this->faker->dateTimeBetween($dateValidation, 'now');
        
        return $this->state(fn (array $attributes) => [
            'statut' => 'payee',
            'date_soumission' => $dateSoumission,
            'validateur_id' => User::factory(),
            'date_validation' => $dateValidation,
            'date_paiement' => $datePaiement,
            'reference_paiement' => $this->faker->regexify('VIR[0-9]{8}'),
            'commentaire_validateur' => $this->faker->optional(0.7)->sentence(),
            'montant_valide' => $attributes['montant_total'] ?? $this->faker->randomFloat(2, 50, 2000),
        ]);
    }

    /**
     * État refusée
     */
    public function refusee(): static
    {
        $dateSoumission = $this->faker->dateTimeBetween('-2 months', '-1 week');
        $dateValidation = $this->faker->dateTimeBetween($dateSoumission, 'now');
        
        return $this->state(fn (array $attributes) => [
            'statut' => 'refusee',
            'date_soumission' => $dateSoumission,
            'validateur_id' => User::factory(),
            'date_validation' => $dateValidation,
            'motif_refus' => $this->faker->sentence(),
            'commentaire_validateur' => $this->faker->sentence(),
            'date_paiement' => null,
            'reference_paiement' => null,
            'montant_valide' => null,
        ]);
    }

    /**
     * Générer un numéro de note de frais unique
     */
    private function generateNumero(): string
    {
        $year = $this->faker->numberBetween(2024, 2025);
        $sequence = $this->faker->numberBetween(1, 999);
        
        return 'NF-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
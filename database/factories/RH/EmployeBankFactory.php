<?php

declare(strict_types=1);

namespace Database\Factories\RH;

use App\Models\Core\Bank;
use App\Models\RH\Employe;
use App\Models\RH\EmployeBank;
use Illuminate\Database\Eloquent\Factories\Factory;

final class EmployeBankFactory extends Factory
{
    protected $model = EmployeBank::class;

    public function definition(): array
    {
        return [
            'employe_id' => Employe::factory(),
            'bank_id' => Bank::inRandomOrder()->first()?->id ?? Bank::factory(),
            'iban' => $this->generateFrenchIban(),
            'bic' => $this->generateBic(),
            'bridge_id' => $this->faker->uuid(),
        ];
    }

    /**
     * Compte avec IBAN français spécifique
     */
    public function withFrenchIban(): static
    {
        return $this->state(fn (array $attributes) => [
            'iban' => $this->generateFrenchIban(),
            'bic' => $this->generateBic(),
        ]);
    }

    /**
     * Compte avec des données Bridge spécifiques
     */
    public function withBridgeData(): static
    {
        return $this->state(fn (array $attributes) => [
            'bridge_id' => $this->faker->uuid(),
        ]);
    }

    /**
     * Compte sans données Bridge (compte manuel)
     */
    public function withoutBridgeData(): static
    {
        return $this->state(fn (array $attributes) => [
            'bridge_id' => null,
        ]);
    }

    /**
     * Compte avec une banque spécifique
     */
    public function forBank(Bank $bank): static
    {
        return $this->state(fn (array $attributes) => [
            'bank_id' => $bank->id,
        ]);
    }

    /**
     * Compte avec IBAN invalide (pour les tests d'erreur)
     */
    public function withInvalidIban(): static
    {
        return $this->state(fn (array $attributes) => [
            'iban' => 'INVALID_IBAN_FORMAT',
            'bic' => 'INVALID',
        ]);
    }

    /**
     * Compte principal (premier compte de l'employé)
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'iban' => $this->generateFrenchIban(),
            'bic' => $this->generateBic(),
            'bridge_id' => $this->faker->uuid(),
        ]);
    }

    /**
     * Compte secondaire
     */
    public function secondary(): static
    {
        return $this->state(fn (array $attributes) => [
            'iban' => $this->generateFrenchIban(),
            'bic' => $this->generateBic(),
            'bridge_id' => null, // Souvent ajouté manuellement
        ]);
    }

    /**
     * Génère un IBAN français valide
     */
    private function generateFrenchIban(): string
    {
        // Format IBAN français : FR76 XXXX XXXX XXXX XXXX XXXX XXX
        $bankCode = $this->faker->numerify('####');
        $branchCode = $this->faker->numerify('#####');
        $accountNumber = $this->faker->numerify('###########');

        // Calcul de la clé de contrôle (simplifié pour les tests)
        $checkDigits = $this->faker->numberBetween(10, 99);

        return "FR{$checkDigits} {$bankCode} {$branchCode} {$accountNumber}";
    }

    /**
     * Génère un code BIC réaliste
     */
    private function generateBic(): string
    {
        $bankCodes = [
            'BNPAFRPP', // BNP Paribas
            'CEPAFRPP', // Crédit Agricole
            'SOGEFRPP', // Société Générale
            'CCBPFRPP', // Crédit Mutuel
            'AGRIFRPP', // Crédit Agricole
            'CMCIFRPP', // CIC
            'BOUSFRPP', // Boursorama
            'QNTOFRP1', // Crédit Mutuel Arkéa
            'PSST FR PP', // La Banque Postale
            'HELLOFRP', // Hello Bank
        ];

        return $this->faker->randomElement($bankCodes);
    }
}

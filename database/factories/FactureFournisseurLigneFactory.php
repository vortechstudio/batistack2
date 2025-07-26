<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Commerce\FactureFournisseur;
use App\Models\Commerce\FactureFournisseurLigne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class FactureFournisseurLigneFactory extends Factory
{
    protected $model = FactureFournisseurLigne::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'libelle' => $this->faker->word(),
            'description' => $this->faker->text(),
            'qte' => $this->faker->randomFloat(),
            'puht' => $this->faker->randomFloat(),
            'amount_ht' => $this->faker->randomFloat(),
            'amount_ttc' => $this->faker->randomFloat(),
            'tva_rate' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'facture_fournisseur_id' => FactureFournisseur::factory(),
        ];
    }
}

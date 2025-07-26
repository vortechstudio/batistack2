<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Commerce\FactureFournisseur;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class FactureFournisseurFactory extends Factory
{
    protected $model = FactureFournisseur::class;

    public function definition(): array
    {
        return [
            'num_facture' => $this->faker->word(),
            'date_facture' => Carbon::now(),
            'date_echeance' => Carbon::now(),
            'status' => $this->faker->word(),
            'amount_ht' => $this->faker->randomFloat(),
            'amount_ttc' => $this->faker->randomFloat(),
            'amount_du' => $this->faker->randomFloat(),
            'notes' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'tiers_id' => Tiers::factory(),
        ];
    }
}

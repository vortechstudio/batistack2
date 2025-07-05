<?php

namespace Database\Factories\Commerce;

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Avoir;
use App\Models\Commerce\Facture;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AvoirFactory extends Factory
{
    protected $model = Avoir::class;

    public function definition(): array
    {
        $dateAvoir = $this->faker->date();
        $amount_ht = $this->faker->randomFloat(2);
        $amount_ttc = $amount_ht * 1.2;

        return [
            'num_avoir' => $this->faker->word(),
            'date_avoir' => $dateAvoir,
            'amount_ht' => $amount_ht,
            'amount_ttc' => $amount_ttc,
            'raison' => $this->faker->boolean ? $this->faker->word() : null,

            'facture_id' => $this->faker->boolean ? Facture::all()->random()->id : null,
            'tiers_id' => Tiers::all()->random()->id,
            'chantiers_id' => $this->faker->boolean ? Chantiers::all()->random()->id : null,
            'responsable_id' => User::first()->id,
        ];
    }
}

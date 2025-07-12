<?php

namespace Database\Factories;

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\frais;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class fraisFactory extends Factory
{
    protected $model = Frais::class;

    public function definition(): array
    {
        return [
            'category' => $this->faker->word(),
            'libelle' => $this->faker->word(),
            'description' => $this->faker->text(),
            'date_frais' => Carbon::now(),
            'amount_ht' => $this->faker->randomFloat(),
            'tva_rate' => $this->faker->randomFloat(),
            'amount_ttc' => $this->faker->randomFloat(),
            'justificatif_uri' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'chantiers_id' => Chantiers::factory(),
        ];
    }
}

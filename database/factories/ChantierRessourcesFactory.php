<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Chantiers\ChantierRessources;
use App\Models\Chantiers\Chantiers;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ChantierRessourcesFactory extends Factory
{
    protected $model = ChantierRessources::class;

    public function definition(): array
    {
        return [
            'role' => $this->faker->word(),
            'date_affectation' => Carbon::now(),
            'date_fin' => Carbon::now(),
            'amount_fee' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'chantiers_id' => Chantiers::factory(),
        ];
    }
}

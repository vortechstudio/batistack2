<?php

declare(strict_types=1);

namespace Database\Factories\Chantiers;

use App\Models\Chantiers\ChantierIntervention;
use App\Models\Chantiers\Chantiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ChantierInterventionFactory extends Factory
{
    protected $model = ChantierIntervention::class;

    public function definition(): array
    {
        return [
            'date_intervention' => Carbon::now(),
            'description' => $this->faker->text(),
            'temps' => $this->faker->randomFloat(2, 1, 2),
            'facturable' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'chantiers_id' => Chantiers::factory(),
            'intervenant_id' => User::factory(),
        ];
    }
}

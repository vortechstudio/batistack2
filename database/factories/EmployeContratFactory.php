<?php

namespace Database\Factories;

use App\Models\RH\EmployeContrat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeContratFactory extends Factory
{
    protected $model = EmployeContrat::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'date_debut' => Carbon::now(),
            'date_fin' => Carbon::now(),
            'salaire_horaire' => $this->faker->randomFloat(),
            'heure_travail' => $this->faker->randomFloat(),
            'status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

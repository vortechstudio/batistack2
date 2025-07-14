<?php

namespace Database\Factories\RH;

use App\Models\RH\EmployeContrat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeContratFactory extends Factory
{
    protected $model = EmployeContrat::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['cdi', 'cdd', 'interim', 'apprenti']),
            'date_debut' => Carbon::now()->subDays(rand(10, 900)),
            'date_fin' => null,
            'salaire_horaire' => $this->faker->randomFloat(2,9,25),
            'heure_travail' => 39,
            'status' => $this->faker->randomElement(['actif', 'inactif']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\RH\Employe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeFactory extends Factory
{
    protected $model = Employe::class;

    public function definition(): array
    {
        return [
            'civility' => $this->faker->word(),
            'nom' => $this->faker->word(),
            'prenom' => $this->faker->word(),
            'adresse' => $this->faker->word(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->word(),
            'telephone' => $this->faker->word(),
            'portable' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'poste' => $this->faker->word(),
            'date_embauche' => Carbon::now(),
            'date_sortie' => Carbon::now(),
            'type_contrat' => $this->faker->word(),
            'salaire_base' => $this->faker->randomFloat(),
            'status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

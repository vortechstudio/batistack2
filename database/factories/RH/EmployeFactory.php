<?php

namespace Database\Factories\RH;

use App\Models\RH\Employe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeFactory extends Factory
{
    protected $model = Employe::class;

    public function definition(): array
    {
        return [
            'civility' => $this->faker->randomElement(['Monsieur', 'Madame', 'Mademoiselle']),
            'nom' => $this->faker->firstName,
            'prenom' => $this->faker->lastName,
            'adresse' => $this->faker->streetAddress,
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city,
            'telephone' => $this->faker->e164PhoneNumber,
            'portable' => $this->faker->e164PhoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'poste' => $this->faker->jobTitle,
            'date_embauche' => Carbon::now()->subDays(rand(60,800)),
            'date_sortie' => $this->faker->boolean ? Carbon::now()->subDays(rand(10, 50)) : null,
            'type_contrat' => $this->faker->randomElement(['cdi', 'cdd', 'interim', 'apprenti']),
            'salaire_base' => $this->faker->randomFloat(2, 1000, 5000),
            'status' => $this->faker->randomElement(['actif', 'inactif', 'congé', 'maladie']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

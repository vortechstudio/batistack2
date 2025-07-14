<?php

namespace Database\Factories\RH;

use App\Models\RH\Employe;
use App\Models\RH\EmployeAbscence;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeAbscenceFactory extends Factory
{
    protected $model = EmployeAbscence::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'date_debut' => Carbon::now(),
            'date_fin' => Carbon::now(),
            'motif' => $this->faker->word(),
            'status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'employe_id' => Employe::factory(),
        ];
    }
}

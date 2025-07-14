<?php

namespace Database\Factories\RH;

use App\Models\Chantiers\Chantiers;
use App\Models\RH\Employe;
use App\Models\RH\EmployePointage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployePointageFactory extends Factory
{
    protected $model = EmployePointage::class;

    public function definition(): array
    {
        return [
            'date' => Carbon::now(),
            'heure_arrive' => Carbon::now(),
            'heure_depart' => Carbon::now(),
            'heure_travaillees' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'employe_id' => Employe::factory(),
            'chantiers_id' => Chantiers::factory(),
        ];
    }
}

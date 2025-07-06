<?php

namespace Database\Factories;

use App\Models\RH\Employe;
use App\Models\RH\EmployeInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeInfoFactory extends Factory
{
    protected $model = EmployeInfo::class;

    public function definition(): array
    {
        return [
            'nationnality' => $this->faker->word(),
            'num_cni' => $this->faker->word(),
            'num_secu' => $this->faker->word(),
            'num_passport' => $this->faker->word(),
            'date_naissance' => Carbon::now(),
            'lieu_naissance' => $this->faker->word(),
            'pays_naissance' => $this->faker->word(),
            'num_permis_btp' => $this->faker->word(),
            'exp_permis_btp' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'employe_id' => Employe::factory(),
        ];
    }
}

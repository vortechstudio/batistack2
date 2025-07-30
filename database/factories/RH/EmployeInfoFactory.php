<?php

declare(strict_types=1);

namespace Database\Factories\RH;

use App\Models\RH\Employe;
use App\Models\RH\EmployeInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class EmployeInfoFactory extends Factory
{
    protected $model = EmployeInfo::class;

    public function definition(): array
    {
        return [
            'nationnality' => $this->faker->country,
            'num_cni' => $this->faker->numerify("########"),
            'num_secu' => $this->faker->numerify("##############"),
            'num_passport' => $this->faker->boolean ? $this->faker->randomNumber(8) : null,
            'date_naissance' => Carbon::now()->subYears(rand(18, 60)),
            'lieu_naissance' => $this->faker->city,
            'pays_naissance' => $this->faker->country(),
            'num_permis_btp' => $this->faker->boolean ? $this->faker->numerify("########") : null,
            'exp_permis_btp' => $this->faker->boolean ? Carbon::now()->addYears(rand(1, 5)) : null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'employe_id' => Employe::factory(),
        ];
    }
}

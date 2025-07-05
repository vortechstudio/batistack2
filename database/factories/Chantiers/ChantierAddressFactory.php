<?php

declare(strict_types=1);

namespace Database\Factories\Chantiers;

use App\Models\Chantiers\ChantierAddress;
use App\Models\Chantiers\Chantiers;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ChantierAddressFactory extends Factory
{
    protected $model = ChantierAddress::class;

    public function definition(): array
    {
        return [
            'address' => $this->faker->address(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city,
            'pays' => $this->faker->country,

            'chantiers_id' => Chantiers::factory(),
        ];
    }
}

<?php

namespace Database\Factories\Tiers;

use App\Models\Tiers\TiersAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class TiersAddressFactory extends Factory
{
    protected $model = TiersAddress::class;

    public function definition(): array
    {
        return [
            'address' => $this->faker->streetAddress(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city(),
            'pays' => "France",
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories\Core;

use App\Models\Core\PlanComptable;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PlanComptableFactory extends Factory
{
    protected $model = PlanComptable::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->numerify('###'),
            'account' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(['Actif', 'Passif', 'Charges', 'Revenus']),
            'lettrage' => $this->faker->boolean(),
            'principal' => $this->faker->optional()->word(),
            'initial' => $this->faker->optional()->randomFloat(2, 0, 10000),
        ];
    }

    public function revenus(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Revenus',
        ]);
    }

    public function charges(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Charges',
        ]);
    }

    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Actif',
        ]);
    }

    public function passif(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Passif',
        ]);
    }
}

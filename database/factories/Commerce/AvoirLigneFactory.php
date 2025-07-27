<?php

declare(strict_types=1);

namespace Database\Factories\Commerce;

use App\Models\Commerce\Avoir;
use App\Models\Commerce\AvoirLigne;
use Illuminate\Database\Eloquent\Factories\Factory;

final class AvoirLigneFactory extends Factory
{
    protected $model = AvoirLigne::class;

    public function definition(): array
    {
        $qte = $this->faker->randomFloat(2);
        $puht = $this->faker->randomFloat(2, 1, 7000);

        return [
            'type_avoir' => array_rand([
                'product' => 'product',
                'service' => 'service',
                'fabrication' => 'fabrication',
            ]),
            'libelle' => $this->faker->word(),
            'description' => $this->faker->boolean(25) ? $this->faker->text() : null,
            'qte' => $qte,
            'puht' => $puht,
            'amount_ht' => $qte * $puht,
            'tva_rate' => 19.6,

            'avoir_id' => Avoir::factory(),
        ];
    }
}

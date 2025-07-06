<?php

declare(strict_types=1);

namespace Database\Factories\Commerce;

use App\Models\Commerce\Facture;
use App\Models\Commerce\FactureLigne;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FactureLigneFactory extends Factory
{
    protected $model = FactureLigne::class;

    public function definition(): array
    {
        $qte = $this->faker->randomFloat(2);
        $puht = $this->faker->randomFloat(2, 1, 7000);

        return [
            'type' => array_rand([
                'product' => 'product',
                'service' => 'service',
                'fabrication' => 'fabrication',
            ]),
            'libelle' => $this->faker->word(),
            'description' => $this->faker->boolean ? $this->faker->text() : null,
            'qte' => $qte,
            'puht' => $puht,
            'amount_ht' => $qte * $puht,
            'tva_rate' => 19.6,

            'facture_id' => Facture::factory(),
        ];
    }
}

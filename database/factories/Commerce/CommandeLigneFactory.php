<?php

namespace Database\Factories\Commerce;

use App\Models\Commerce\Commande;
use App\Models\Commerce\CommandeLigne;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommandeLigneFactory extends Factory
{
    protected $model = CommandeLigne::class;

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
            'description' => $this->faker->boolean(25) ? $this->faker->text() : null,
            'qte' => $qte,
            'puth' => $puht,
            'amount_ht' => $qte * $puht,
            'tva_rate' => 19.6,

            'commande_id' => Commande::factory(),
        ];
    }
}

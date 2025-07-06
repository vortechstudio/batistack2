<?php

declare(strict_types=1);

namespace Database\Factories\Commerce;

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Commande;
use App\Models\Commerce\Devis;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    public function definition(): array
    {
        $dateCommande = $this->faker->date();
        $amount_ht = $this->faker->randomFloat(2);
        $amount_ttc = $amount_ht * 1.2;

        return [
            'num_commande' => $this->faker->word(),
            'date_commande' => $dateCommande,
            'status' => array_rand([
                'pending' => 'pending',
                'confirmed' => 'confirmed',
                'waiting' => 'waiting',
                'delivered' => 'delivered',
                'canceled' => 'canceled',
            ]),
            'amount_ht' => $amount_ht,
            'amount_ttc' => $amount_ttc,

            'devis_id' => $this->faker->boolean(25) ? Devis::all()->random()->id : null,
            'chantiers_id' => $this->faker->boolean(25) ? Chantiers::all()->random()->id : null,
            'tiers_id' => Tiers::all()->random()->id,
            'responsable_id' => User::first()->id,
        ];
    }
}

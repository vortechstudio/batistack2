<?php

namespace Database\Factories\Commerce;

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Commande;
use App\Models\Commerce\Facture;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FactureFactory extends Factory
{
    protected $model = Facture::class;

    public function definition(): array
    {
        $dateFacture = $this->faker->date();
        $amount_ht = $this->faker->randomFloat(2);
        $amount_ttc = $amount_ht*1.2;

        return [
            'num_facture' => $this->faker->word(),
            'type_facture' => array_rand([
                'acompte' => 'acompte',
                'situation' => 'situation',
                'final' => 'final',
            ]),
            'date_facture' => $dateFacture,
            'date_echeance' => $this->faker->boolean ? Carbon::createFromTimestamp(strtotime($dateFacture))->addDays(rand(14,60)) : $dateFacture,
            'status' => array_rand([
                'non_payer' => 'non_payer',
                'partiellement' => 'partiellement',
                'payer' => 'payer',
                'retard' => 'retard',
            ]),
            'amount_ht' => $amount_ht,
            'amount_ttc' => $amount_ttc,
            'notes' => $this->faker->boolean ? $this->faker->word() : null,

            'commande_id' => $this->faker->boolean() ? Commande::all()->random()->id : null,
            'chantiers_id' => $this->faker->boolean() ? Chantiers::all()->random()->id : null,
            'tiers_id' => Tiers::all()->random()->id,
            'responsable_id' => User::first()->id,
        ];
    }
}

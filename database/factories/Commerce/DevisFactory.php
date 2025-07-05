<?php

declare(strict_types=1);

namespace Database\Factories\Commerce;

use App\Helpers\Helpers;
use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Devis;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class DevisFactory extends Factory
{
    protected $model = Devis::class;

    public function definition(): array
    {
        $dateDevis = $this->faker->date();
        $amount_ht = $this->faker->randomFloat(2);
        $amount_ttc = $amount_ht * 1.2;

        return [
            'num_devis' => Helpers::generateCodeDevis(),
            'date_devis' => Carbon::now()->between(now()->subYear(), now()),
            'date_validate' => $this->faker->boolean(25) ? Carbon::createFromTimestamp(strtotime($dateDevis))->addDay() : null,
            'status' => array_rand([
                'draft' => 'draft',
                'submit' => 'submit',
                'accepted' => 'accepted',
                'rejected' => 'rejected',
                'cancelled' => 'cancelled',
            ]),
            'amount_ht' => $amount_ht,
            'amount_ttc' => $amount_ttc,
            'notes' => $this->faker->boolean(25) ? $this->faker->word() : null,

            'chantiers_id' => Chantiers::factory(),
            'tiers_id' => Tiers::factory(),
            'responsable_id' => User::factory(),
        ];
    }
}

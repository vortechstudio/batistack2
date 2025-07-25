<?php

declare(strict_types=1);

namespace Database\Factories\Tiers;

use App\Models\Core\ConditionReglement;
use App\Models\Core\ModeReglement;
use App\Models\Core\PlanComptable;
use App\Models\Tiers\TiersFournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TiersFournisseurFactory extends Factory
{
    protected $model = TiersFournisseur::class;

    public function definition(): array
    {
        return [
            'tva' => $this->faker->boolean(),
            'num_tva' => $this->faker->word(),
            'rem_relative' => random_int(0, 100),
            'rem_fixe' => random_int(0, 100),
            'mode_reglement_id' => ModeReglement::all()->random()->id,

            'code_comptable_general' => PlanComptable::all()->random()->id,
            'code_comptable_fournisseur' => PlanComptable::all()->random()->id,
            'condition_reglement_id' => ConditionReglement::all()->random()->id,
        ];
    }
}

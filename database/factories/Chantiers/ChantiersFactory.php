<?php

declare(strict_types=1);

namespace Database\Factories\Chantiers;

use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ChantiersFactory extends Factory
{
    protected $model = Chantiers::class;

    public function definition(): array
    {
        $debut = Carbon::now()->addDays(rand(1, 180))->subDays(rand(1, 360));
        $fin = $debut->addDays(rand(1, 360))->subDays(rand(1, 360));

        return [
            'libelle' => $this->faker->word(),
            'description' => $this->faker->text(),
            'date_debut' => $debut,
            'date_fin_prevu' => $fin,
            'date_fin_reel' => $fin->addDays(rand(15, 45)),
            'status' => $this->faker->randomElement(['planifie', 'progress', 'terminer', 'annuler']),
            'budget_estime' => $this->faker->randomFloat(),
            'budget_reel' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'tiers_id' => Tiers::factory(),
            'responsable_id' => User::factory(),
        ];
    }
}

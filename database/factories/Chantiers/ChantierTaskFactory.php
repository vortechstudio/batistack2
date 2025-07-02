<?php

declare(strict_types=1);

namespace Database\Factories\Chantiers;

use App\Models\Chantiers\Chantiers;
use App\Models\Chantiers\ChantierTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ChantierTaskFactory extends Factory
{
    protected $model = ChantierTask::class;

    public function definition(): array
    {
        $debut = Carbon::now()->addDays(rand(1, 180))->subDays(rand(1, 360));
        $fin = $debut->addDays(rand(1, 360))->subDays(rand(1, 360));

        return [
            'libelle' => $this->faker->word(),
            'description' => $this->faker->text(),
            'date_debut_prevu' => $debut,
            'date_fin_prevue' => $fin,
            'date_debut_reel' => null,
            'date_fin_reel' => null,
            'status' => 'todo',
            'priority' => 'low',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'assigned_id' => User::factory(),
            'chantiers_id' => Chantiers::factory(),
        ];
    }
}

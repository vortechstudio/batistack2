<?php

declare(strict_types=1);

namespace Database\Factories\Chantiers;

use App\Enums\Chantiers\TypeDepenseChantier;
use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ChantierDepenseFactory extends Factory
{
    protected $model = ChantierDepense::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'montant' => $this->faker->randomFloat(),
            'date_depense' => Carbon::now(),
            'type_depense' => array_rand(['materiel' => 'materiel', 'main_oeuvre' => 'main_oeuvre', 'sous_traitance' => 'sous_traitance', 'transport' => 'transport']),
            'invoice_ref' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'tiers_id' => Tiers::factory(),
            'chantiers_id' => Chantiers::factory(),
        ];
    }
}

<?php

namespace Database\Factories\Tiers;

use App\Enums\Tiers\TiersNature;
use App\Enums\Tiers\TiersType;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\Factory;

class TiersFactory extends Factory
{
    protected $model = Tiers::class;

    public function definition(): array
    {
        $nature = \Arr::random(TiersNature::array());
        $type = \Arr::random(TiersType::array());
        $tva = $this->faker->boolean(80);

        return [
            'name' => $this->faker->company(),
            'nature' => $nature,
            'type' => $type,
            'code_tiers' => $nature == 'fournisseur' ? 'FOUR2026-'.rand(1, 600) : 'CLT2026-'.rand(1, 600),
            'siren' => $this->faker->siren(),
            'tva' => $tva,
            'num_tva' => $tva ? $this->faker->vat() : null,
        ];
    }
}

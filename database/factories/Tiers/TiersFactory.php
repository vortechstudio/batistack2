<?php

declare(strict_types=1);

namespace Database\Factories\Tiers;

use App\Enums\Tiers\TiersNature;
use App\Enums\Tiers\TiersType;
use App\Models\Tiers\Tiers;
use Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TiersFactory extends Factory
{
    protected $model = Tiers::class;

    public function definition(): array
    {
        $nature = Arr::random(TiersNature::array());
        $type = Arr::random(TiersType::array());
        $tva = $this->faker->boolean(80);

        return [
            'name' => $this->faker->company(),
            'nature' => $nature,
            'type' => $type,
            'code_tiers' => $nature === 'fournisseur' ? 'FOUR2026-'.random_int(1, 600) : 'CLT2026-'.random_int(1, 600),
            'siren' => $this->faker->randomNumber(14),
            'tva' => $tva,
            'num_tva' => $tva ? $this->faker->vat() : null,
        ];
    }
}

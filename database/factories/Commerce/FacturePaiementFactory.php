<?php

declare(strict_types=1);

namespace Database\Factories\Commerce;

use App\Models\Commerce\Facture;
use App\Models\Commerce\FacturePaiement;
use App\Models\Core\ModeReglement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class FacturePaiementFactory extends Factory
{
    protected $model = FacturePaiement::class;

    public function definition(): array
    {
        return [
            'date_paiement' => Carbon::now(),
            'amount' => $this->faker->randomFloat(),
            'reference' => 'STS'.now()->format('ym').'-00'.rand(10, 99),

            'mode_reglement_id' => ModeReglement::all()->random()->id,
            'facture_id' => Facture::factory(),
        ];
    }
}

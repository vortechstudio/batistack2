<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Commerce\FactureFournisseur;
use App\Models\Commerce\FactureFournisseurPaiement;
use App\Models\Core\ModeReglement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class FactureFournisseurPaiementFactory extends Factory
{
    protected $model = FactureFournisseurPaiement::class;

    public function definition(): array
    {
        return [
            'date_paiement' => Carbon::now(),
            'amount' => $this->faker->randomFloat(),
            'reference' => $this->faker->word(),
            'notes' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'facture_fournisseur_id' => FactureFournisseur::all()->random()->id,
            'mode_reglement_id' => ModeReglement::all()->random()->id,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Helpers\Helpers;
use App\Models\Chantiers\ChantierAddress;
use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\ChantierIntervention;
use App\Models\Chantiers\Chantiers;
use App\Models\Chantiers\ChantierTask;
use App\Models\Commerce\Commande;
use App\Models\Commerce\CommandeLigne;
use App\Models\Commerce\Devis;
use App\Models\Commerce\DevisLigne;
use App\Models\Commerce\Facture;
use App\Models\Commerce\FactureLigne;
use App\Models\Commerce\FacturePaiement;
use App\Models\Core\Company;
use App\Models\Core\ModeReglement;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersAddress;
use App\Models\Tiers\TiersBank;
use App\Models\Tiers\TiersClient;
use App\Models\Tiers\TiersContact;
use App\Models\Tiers\TiersFournisseur;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone_number' => '+33745521533',
            ]);
        }

        if (Company::count() === 0) {
            Company::create();
        }

        if (Tiers::count() === 0) {
            Tiers::factory(random_int(5, 30))->create();

            foreach (Tiers::all() as $tiers) {
                TiersAddress::factory(random_int(1, 2))->create([
                    'tiers_id' => $tiers->id,
                ]);

                TiersContact::factory(random_int(1, 10))->create([
                    'tiers_id' => $tiers->id,
                ]);

                if ($tiers->nature->value === 'fournisseur') {
                    TiersFournisseur::factory()->create([
                        'tva' => $tiers->tva,
                        'num_tva' => $tiers->num_tva,
                        'tiers_id' => $tiers->id,
                    ]);
                } else {
                    TiersClient::factory()->create([
                        'tva' => $tiers->tva,
                        'num_tva' => $tiers->num_tva,
                        'tiers_id' => $tiers->id,
                    ]);
                }

                TiersBank::factory()->create([
                    'tiers_id' => $tiers->id,
                ]);
            }
        }

        if (Chantiers::count() === 0) {
            Chantiers::factory(rand(5,30))->create([
                "tiers_id" => Tiers::all()->random()->id,
                "responsable_id" => User::first()->id,
            ]);

            foreach (Chantiers::all() as $chantier) {
                ChantierAddress::factory()
                    ->create([
                        'chantiers_id' => $chantier->id,
                    ]);

                ChantierDepense::factory(rand(1,15))->create([
                    'chantiers_id' => $chantier->id,
                    'tiers_id' => $chantier->tiers_id,
                ]);

                ChantierTask::factory(rand(1,10))->create([
                    'chantiers_id' => $chantier->id,
                    'assigned_id' => $chantier->responsable_id,
                ]);

                ChantierIntervention::factory(rand(1,5))->create([
                    'chantiers_id' => $chantier->id,
                    'intervenant_id' => $chantier->responsable_id,
                ]);
            }
        }

        if (Devis::count() === 0) {
            Devis::factory(rand(1,10))->create([
                'chantiers_id' => Chantiers::all()->random()->id,
                'tiers_id' => Tiers::all()->random()->id,
                'responsable_id' => User::first()->id,
                'num_devis' => Helpers::generateCodeDevis(),
            ]);

            foreach (Devis::all() as $devis) {
                DevisLigne::factory(rand(1,5))->create([
                    'devis_id' => $devis->id,
                ]);
            }
        }

        if (Commande::count() === 0) {
            Commande::factory(rand(1,10))->create([
                'num_commande' => Helpers::generateCodeCommande(),
            ]);

            foreach (Commande::all() as $commande) {
                CommandeLigne::factory(rand(1,5))->create([
                    'commande_id' => $commande->id,
                ]);
            }
        }

        if (Facture::count() === 0) {
            Facture::factory(rand(1,30))->create([
                'num_facture' => Helpers::generateCodeFacture(),
            ]);

            foreach (Facture::all() as $facture) {
                FactureLigne::factory(rand(1,5))->create([
                    'facture_id' => $facture->id,
                ]);

                if($facture->status->value == 'payer') {
                    FacturePaiement::create([
                        'facture_id' => $facture->id,
                        'date_paiement' => $facture->date_echeance->addDays(rand(2,5)),
                        'amount' => $facture->amount_ttc,
                        'mode_reglement_id' => ModeReglement::all()->random()->id,
                        'reference' => "STS".now()->format('ym').'-00'.rand(10,99)
                    ]);
                }
            }
        }
    }
}

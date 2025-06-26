<?php

namespace Database\Seeders;

use App\Models\Core\Company;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersAddress;
use App\Models\Tiers\TiersBank;
use App\Models\Tiers\TiersClient;
use App\Models\Tiers\TiersContact;
use App\Models\Tiers\TiersFournisseur;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if(User::count() === 0) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        if(Company::count() === 0) {
            Company::create();
        }

        if(Tiers::count() === 0) {
            Tiers::factory(rand(5,30))->create();

            foreach (Tiers::all() as $tiers) {
                TiersAddress::factory(rand(1,2))->create([
                    'tiers_id' => $tiers->id,
                ]);

                TiersContact::factory(rand(1,10))->create([
                    'tiers_id' => $tiers->id,
                ]);

                if($tiers->nature->value === 'fournisseur') {
                    TiersFournisseur::factory()->create([
                        'tva' => $tiers->tva,
                        'num_tva' => $tiers->num_tva,
                        'tiers_id' =>  $tiers->id,
                    ]);
                } else {
                    TiersClient::factory()->create([
                        'tva' => $tiers->tva,
                        'num_tva' => $tiers->num_tva,
                        'tiers_id' =>  $tiers->id,
                    ]);
                }

                TiersBank::factory()->create([
                    'tiers_id' => $tiers->id,
                ]);
            }
        }
    }
}

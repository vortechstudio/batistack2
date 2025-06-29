<?php

namespace App\Console\Commands;

use App\Models\Core\City;
use Illuminate\Console\Command;
use Storage;

class InstallCityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (City::count() == 0) {
            $cities = collect(json_decode(Storage::disk('public')->get('private/cities.json'), true))->toArray();

            $chunks = array_chunk($cities, 500);
            $totalChunks = count($chunks);
            $this->info('Nombre de tranches : '.$totalChunks);

            foreach ($chunks as $i => $chunk) {
                $this->info('Traitement de la tranche '.($i + 1)."/{$totalChunks}");

                $bar = $this->output->createProgressBar(count($chunk));
                foreach ($chunk as $city) {
                    $latLong = explode(',', $city['coordonnees_gps']);

                    City::create([
                        'city' => $city['Nom_commune'],
                        'postal_code' => $city['Code_postal'],
                        'latitude' => $latLong[0] ?? null,
                        'longitude' => $latLong[1] ?? null,
                    ]);
                    $bar->advance();
                }
                $bar->finish();
            }
        }
    }
}

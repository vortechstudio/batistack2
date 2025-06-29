<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Core\Country;
use Http;
use Illuminate\Console\Command;

final class InstallCountryCommand extends Command
{
    protected $signature = 'install:country';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Country::count() === 0) {
            $this->info('Installation des informations des pays');

            $countries = Http::withoutVerifying()
                ->get('https://gist.githubusercontent.com/revolunet/6173043/raw/222c4537affb1bdecbabcec51143742709aa0b6e/countries-FR.json')
                ->json();

            $bar = $this->output->createProgressBar(count($countries));
            $bar->start();

            foreach ($countries as $country) {
                Country::create([
                    'name' => $country,
                ]);
                $bar->advance();
            }
            $bar->finish();
        }
    }
}

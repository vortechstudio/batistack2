<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Core\Bank;
use App\Services\Bridge;
use Exception;
use Illuminate\Console\Command;
use Log;

final class InstallBankCommand extends Command
{
    protected $signature = 'install:bank';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Bank::count() === 0) {
            $bridge = new Bridge();
            $this->info('Installation des informations des pays');
            try {
                $call = $bridge->get('/providers?limit=500&country_code=FR')['resources'];
                $progress = $this->output->createProgressBar(count($call));
                $progress->start();

                foreach ($call as $bank) {
                    Bank::create([
                        'bridge_id' => $bank['id'],
                        'name' => $bank['name'],
                        'logo_bank' => $bank['images']['logo'],
                        'status_aggegation' => isset($bank['health_status']['aggregation']['status']) ? $bank['health_status']['aggregation']['status'] : null,
                        'status_payment' => isset($bank['health_status']['single_payment']['status']) ? $bank['health_status']['single_payment']['status'] : null,
                    ]);
                    $progress->advance();
                }

                $progress->finish();

            } catch (Exception $exception) {
                Log::error($exception);
                $this->error("Erreur lors de l'importation des banques");
            }
        }
    }
}

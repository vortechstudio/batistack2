<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Core\Bank;
use App\Services\Bridge;
use Illuminate\Console\Command;

final class InstallBankCommand extends Command
{
    protected $signature = 'install:bank';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Bank::count() === 0) {
            $bridge = new Bridge;
            $banks = $bridge->get('/providers?limit=500&country_code=FR');
            $bar = $this->output->createProgressBar(count($banks));
            $bar->start();

            foreach ($banks['resources'] as $bank) {
                Bank::updateOrCreate([
                    'bridge_id' => $bank['id'],
                ], [
                    'name' => $bank['name'],
                    'group_name' => $bank['group_name'] ?? null,
                    'logo' => $bank['images']['logo'],
                    'status_aggregate' => $bank['health_status']['aggregation']['status'] ?? null,
                    'status_payment' => $bank['health_status']['single_payment']['status'] ?? null,
                ]);
                $bar->advance();
            }
            $bar->finish();
        }
    }
}

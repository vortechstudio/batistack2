<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Core\Bank;
use App\Services\Powens;
use Illuminate\Console\Command;

final class InstallBankCommand extends Command
{
    protected $signature = 'install:bank';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Bank::count() === 0) {
            $powens = new Powens();
            $banks = $powens->get('connectors', ["country_codes" => "fr"]);
            $bar = $this->output->createProgressBar(count($banks));
            $bar->start();

            foreach ($banks['connectors'] as $bank) {
                Bank::updateOrCreate([
                    "powens_uuid" => $bank['uuid']
                ], [
                    "powens_uuid" => $bank['uuid'],
                    "name" => $bank['name'],
                    "status" => $bank['stability']['status'],
                ]);

                $bar->advance();
            }

            $bar->finish();
        }
    }
}

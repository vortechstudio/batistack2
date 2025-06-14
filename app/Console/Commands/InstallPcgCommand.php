<?php

namespace App\Console\Commands;

use App\Models\Core\PlanComptable;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class InstallPcgCommand extends Command
{
    protected $signature = 'install:pcg';

    protected $description = 'Command description';

    public function handle(): void
    {
        $collects = (new FastExcel)->import(\Storage::disk('public')->path('private/pcg.xlsx'));
        if(PlanComptable::count() === 0) {
            $this->info("Installation du plan comptable général");
            $bar = $this->output->createProgressBar($collects->count());
            $bar->start();

            foreach($collects as $account) {
                PlanComptable::create([
                    'code' => $account['Code'],
                    'account' =>  $account['account'],
                    'type' => $account['type'],
                    'lettrage' =>  $account['lettrage'] == true ? true : false,
                    'principal' =>  $account['principal'],
                    'initial' =>   number_format($account['initial'], 2, ',', ' '),
                ]);
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

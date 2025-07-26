<?php

declare(strict_types=1);

use App\Enums\RH\ProcessEmploye;
use App\Models\RH\EmployeContrat;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('telescope:clear')->hourly();
Schedule::command('update:bank-mouvement')
    ->everyFourHours();

Schedule::call(function () {
    $contracts = EmployeContrat::all();

    foreach ($contracts as $contract) {
        if ($contract->employe->info->process->value === 'contract_sign' && $contract->date_debut->startOfDay() >= now()->startOfDay()) {
            $contract->employe->info->process = ProcessEmploye::TERMINATED;
            $contract->employe->info->save();
        }
    }
})->daily();

<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/test', function () {
    \Bugsnag\BugsnagLaravel\Facades\Bugsnag::notifyException(new RuntimeException("Error !"));
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('settings/company', \App\Livewire\Settings\Company::class)->name('settings.company');
    Route::get('settings/pcg', \App\Livewire\Settings\Plan::class)->name('settings.pcg');
    Route::get('settings/pcg/create', \App\Livewire\Settings\CreatePlan::class)->name('settings.pcg.create');
    Route::get('settings/bank', \App\Livewire\Settings\Bank::class)->name('settings.bank');
    Route::get('settings/bank/create', \App\Livewire\Settings\CreateBank::class)->name('settings.bank.create');
});

Route::get('/api/bank/connect', [\App\Http\Controllers\BankController::class, 'connectAccount'])->name('api.bank.connectAccount');;

require __DIR__.'/auth.php';

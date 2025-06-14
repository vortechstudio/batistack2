<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
    Route::get('settings/bank', \App\Livewire\Settings\Bank::class)->name('settings.bank');
});

require __DIR__.'/auth.php';

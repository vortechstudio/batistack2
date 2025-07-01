<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/test', function () {
    $mdx = new App\Services\Mailbox;
    dd($mdx->getMessageBody(1));
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('notifications', App\Livewire\Core\Pages\Notification::class)->name('notifications');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('settings/company', App\Livewire\Settings\Company::class)->name('settings.company');
    Route::get('settings/pcg', App\Livewire\Settings\Plan::class)->name('settings.pcg');
    Route::get('settings/pcg/create', App\Livewire\Settings\CreatePlan::class)->name('settings.pcg.create');
    Route::get('settings/bank', App\Livewire\Settings\Bank::class)->name('settings.bank');
    Route::get('settings/bank/create', App\Livewire\Settings\CreateBank::class)->name('settings.bank.create');

    Route::prefix('account')->group(function () {
        Route::get('/')->name('account.dashboard');
    });

    Route::prefix('tiers')->group(function () {
        Route::get('/', App\Livewire\Tiers\Dashboard::class)->name('tiers.dashboard');

        Route::prefix('supply')->group(function () {
            Route::get('/', App\Livewire\Tiers\Supply\ListSupply::class)->name('tiers.supply.list');
            Route::get('/create', App\Livewire\Tiers\Supply\CreateSupply::class)->name('tiers.supply.create');
            Route::get('{id}', App\Livewire\Tiers\Supply\ViewSupply::class)->name('tiers.supply.view');
            Route::get('{id}/edit', App\Livewire\Tiers\Supply\EditSupply::class)->name('tiers.supply.edit');
        });

        Route::prefix('customers')->group(function () {
            Route::get('/', App\Livewire\Tiers\Customers\ListCustomers::class)->name('tiers.customers.list');
            Route::get('create', App\Livewire\Tiers\Customers\CreateCustomers::class)->name('tiers.customers.create');
            Route::get('{id}', App\Livewire\Tiers\Customers\ViewCustomers::class)->name('tiers.customers.view');
            Route::get('{id}/edit', App\Livewire\Tiers\Customers\EditCustomers::class)->name('tiers.customers.edit');
        });

    });
});

Route::get('/api/bank/connect', [App\Http\Controllers\BankController::class, 'connectAccount'])->name('api.bank.connectAccount');

require __DIR__.'/auth.php';

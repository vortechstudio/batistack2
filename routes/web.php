<?php

declare(strict_types=1);

use App\Livewire\Humans\Config\Index as ConfigIndex;
use App\Livewire\Humans\Dashboard;
use App\Livewire\Humans\Salarie\Index;
use App\Livewire\Humans\Salarie\Transmission;
use App\Livewire\Humans\Salarie\View;
use App\Livewire\Portail\Salarie\Dashboard as SalarieDashboard;
use App\Livewire\Portail\Salarie\Documents\Index as DocumentsIndex;
use App\Livewire\Portail\Salarie\Documents\Signed;
use App\Services\TesseractService;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/test', function () {

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
    Route::get('settings/app', App\Livewire\Settings\SettingsApp::class)->name('settings.app');
    Route::get('settings/pcg', App\Livewire\Settings\Plan::class)->name('settings.pcg');
    Route::get('settings/pcg/create', App\Livewire\Settings\CreatePlan::class)->name('settings.pcg.create');

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

    Route::prefix('chantiers')->group(function () {
        Route::get('/', App\Livewire\Chantiers\Dashboard::class)->name('chantiers.dashboard');
        Route::get('{id}', App\Livewire\Chantier\View::class)->name('chantiers.view');
    });

    Route::prefix('humans')->group(function() {
        Route::get('/', Dashboard::class)->name('humans.dashboard');

        Route::prefix('salaries')->group(function() {
            Route::get('/', Index::class)->name('humans.salaries.index');
            Route::get('{id}', View::class)->name('humans.salaries.view');
            Route::get('{id}/transmission', Transmission::class)->name('humans.salaries.transmission');
        });

        Route::prefix('config')->group(function () {
            Route::get('/', App\Livewire\Humans\Config\Index::class)->name('humans.config.index');
        });
    });

    Route::prefix('portail')->group(function () {
        Route::prefix('salarie')->group(function() {
            Route::get('/', SalarieDashboard::class)->name('portail.salarie.dashboard');

            Route::prefix('documents')->group(function() {
                Route::get('/', DocumentsIndex::class)->name('portail.salarie.documents');
                Route::get('/signed/{id}', Signed::class)->name('portail.salarie.documents.signed');
            });
        });
    });
});

Route::get('/api/bank/connect', [App\Http\Controllers\BankController::class, 'connectAccount'])->name('api.bank.connectAccount');

require __DIR__.'/auth.php';

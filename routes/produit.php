<?php
declare(strict_types=1);

use App\Livewire\Produit\Dashboard;
use Illuminate\Support\Facades\Route;

Route::prefix('produit')->group(function() {
    Route::get('/', Dashboard::class)->name('produit.dashboard');
});

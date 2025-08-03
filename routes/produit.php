<?php
declare(strict_types=1);

use App\Livewire\Produit\Dashboard;
use Illuminate\Support\Facades\Route;

Route::prefix('produit')->group(function() {
    Route::get('/', Dashboard::class)->name('produit.dashboard');
    Route::prefix('produit')->group(function() {
        Route::get('/', Dashboard::class)->name('produit.produit.index');
        Route::get('/{id}', Dashboard::class)->name('produit.produit.show');
    });
    Route::prefix('service')->group(function() {
        Route::get('/', Dashboard::class)->name('produit.service.index');
        Route::get('/{id}', Dashboard::class)->name('produit.service.show');
    });
    Route::prefix('stock')->group(function() {
        Route::get('/', Dashboard::class)->name('produit.stock.index');
        Route::get('/{id}', Dashboard::class)->name('produit.stock.show');
    });
});

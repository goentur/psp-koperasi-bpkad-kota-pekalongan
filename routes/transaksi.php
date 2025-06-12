<?php

use App\Http\Controllers\Transaksi\SetoranController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    Route::controller(SetoranController::class)->prefix('transaksi')->name('transaksi.')->group(function () {
        Route::middleware('can:transaksi-setoran-index')->controller(SetoranController::class)->prefix('setoran')->name('setoran.')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('data', 'data')->name('data');
            Route::middleware('can:transaksi-setoran-anggota-create')->post('setoran-anggota-create', 'save')->name('setoran-anggota-create');
            Route::middleware('can:transaksi-setoran-create')->post('setoran-create', 'create')->name('setoran-create');
        });
    });
});

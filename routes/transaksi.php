<?php

use App\Http\Controllers\Transaksi\SetoranController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    Route::controller(SetoranController::class)->prefix('transaksi')->name('transaksi.')->group(function () {
        Route::middleware('can:transaksi-setoran-index')->controller(SetoranController::class)->prefix('setoran')->name('setoran.')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('data-tabungan', 'dataTabungan')->name('data-tabungan');
            Route::post('tabungan-baru', 'tabunganBaru')->name('tabungan-baru');
            Route::post('setoran-baru', 'setoranBaru')->name('setoran-baru');
            Route::post('data-pinjaman', 'dataPinjaman')->name('data-pinjaman');
            Route::post('pinjaman-baru', 'pinjamanBaru')->name('pinjaman-baru');
            Route::post('angsuran', 'angsuran')->name('angsuran');
            Route::middleware('can:transaksi-setoran-anggota-create')->post('setoran-anggota-create', 'save')->name('setoran-anggota-create');
            Route::middleware('can:transaksi-setoran-create')->post('setoran-create', 'create')->name('setoran-create');
        });
    });
});

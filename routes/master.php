<?php

use App\Http\Controllers\Master\AnggotaController;
use App\Http\Controllers\Master\JenisTabunganController;
use App\Http\Controllers\Master\SatuanKerjaController;
use App\Http\Controllers\Master\TempatSaldoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    Route::controller(SatuanKerjaController::class)->prefix('satuan-kerja')->name('satuan-kerja.')->group(function () {
        Route::middleware('can:satuan-kerja-index')->post('data', 'data')->name('data');
        Route::post('list', 'list')->name('list');
    });
    Route::controller(JenisTabunganController::class)->prefix('jenis-tabungan')->name('jenis-tabungan.')->group(function () {
        Route::middleware('can:jenis-tabungan-index')->post('data', 'data')->name('data');
        Route::post('list', 'list')->name('list');
    });
    Route::controller(TempatSaldoController::class)->prefix('tempat-saldo')->name('tempat-saldo.')->group(function () {
        Route::middleware('can:tempat-saldo-index')->post('data', 'data')->name('data');
        Route::post('list', 'list')->name('list');
    });
    Route::controller(AnggotaController::class)->prefix('anggota')->name('anggota.')->group(function () {
        Route::middleware('can:anggota-index')->post('data', 'data')->name('data');
        Route::post('list', 'list')->name('list');
    });
    Route::resource('satuan-kerja', SatuanKerjaController::class);
    Route::resource('jenis-tabungan', JenisTabunganController::class);
    Route::resource('tempat-saldo', TempatSaldoController::class);
    Route::resource('anggota', AnggotaController::class);
});

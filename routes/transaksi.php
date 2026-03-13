<?php

use App\Http\Controllers\Transaksi\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    Route::controller(TransaksiController::class)->prefix('transaksi')->name('transaksi.')->group(function () {
        Route::middleware('can:transaksi-index')->controller(TransaksiController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('data-tabungan', 'dataTabungan')->name('data-tabungan');
            Route::post('tabungan-baru', 'tabunganBaru')->name('tabungan-baru');
            Route::post('setoran-atau-tarik', 'setoranAtauTarik')->name('setoran-atau-tarik');
            Route::post('data-pinjaman', 'dataPinjaman')->name('data-pinjaman');
            Route::post('pinjaman-baru', 'pinjamanBaru')->name('pinjaman-baru');
            Route::post('angsuran', 'angsuran')->name('angsuran');
            Route::middleware('can:transaksi-anggota-create')->post('setoran-anggota-create', 'save')->name('setoran-anggota-create');
            Route::middleware('can:transaksi-create')->post('setoran-create', 'create')->name('setoran-create');
        });
        Route::middleware('can:transaksi-persetujuan')->controller(TransaksiController::class)->group(function () {
            Route::get('persetujuan-simpanan', 'persetujuanSimpanan')->name('persetujuanSimpanan');
            Route::post('data-transaksi-simpanan', 'dataTransaksiSimpanan')->name('dataTransaksiSimpanan');
            Route::post('terima-transaksi-simpanan', 'terimaTransaksiSimpanan')->name('terimaTransaksiSimpanan');

            Route::get('persetujuan-angsuran', 'persetujuanAngsuran')->name('persetujuanAngsuran');
            Route::post('data-transaksi-angsuran', 'dataTransaksiAngsuran')->name('dataTransaksiAngsuran');
            Route::post('terima-transaksi-angsuran', 'terimaTransaksiAngsuran')->name('terimaTransaksiAngsuran');
        });
    });
});

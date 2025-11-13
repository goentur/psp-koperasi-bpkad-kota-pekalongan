<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('data-dashboard', [DashboardController::class, 'dataDashboard'])->name('data-dashboard');
    Route::post('data-anggota-dashboard', [DashboardController::class, 'dataAnggotaDashboard'])->name('data-anggota-dashboard');
});

require __DIR__ . '/user.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/master.php';
require __DIR__ . '/transaksi.php';
require __DIR__ . '/auth.php';

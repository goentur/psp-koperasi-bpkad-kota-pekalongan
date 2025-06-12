<?php

namespace App\Providers;

use App\Models\Saldo;
use App\Models\Tabungan;
use App\Observers\SaldoObserver;
use App\Observers\TabunganObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Tabungan::observe(TabunganObserver::class);
        Saldo::observe(SaldoObserver::class);
    }
}

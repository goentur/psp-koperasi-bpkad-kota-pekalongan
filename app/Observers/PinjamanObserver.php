<?php

namespace App\Observers;

use App\Models\Pinjaman;
use App\Models\Saldo;
use App\Support\KodeRefresnsiRiwayatSaldo;
use App\Support\SaldoContext;

class PinjamanObserver
{
    /**
     * Handle the Pinjaman "created" event.
     */
    public function created(Pinjaman $pinjaman): void
    {
        $saldo = Saldo::where('utama', true)->first();
        if ($saldo) {
            SaldoContext::set('PINJAMAN');
            KodeRefresnsiRiwayatSaldo::set("A-" . $pinjaman->anggota_id . "-" . $pinjaman->id);
            $saldo->nominal -= $pinjaman->nominal;
            $saldo->save();
            SaldoContext::clear();
            KodeRefresnsiRiwayatSaldo::clear();
        }
    }

    /**
     * Handle the Pinjaman "updated" event.
     */
    public function updated(Pinjaman $pinjaman): void
    {
        //
    }

    /**
     * Handle the Pinjaman "deleted" event.
     */
    public function deleted(Pinjaman $pinjaman): void
    {
        //
    }

    /**
     * Handle the Pinjaman "restored" event.
     */
    public function restored(Pinjaman $pinjaman): void
    {
        //
    }

    /**
     * Handle the Pinjaman "force deleted" event.
     */
    public function forceDeleted(Pinjaman $pinjaman): void
    {
        //
    }
}

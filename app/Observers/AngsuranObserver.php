<?php

namespace App\Observers;

use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Saldo;
use App\Support\KodeRefresnsiRiwayatSaldo;
use App\Support\SaldoContext;

class AngsuranObserver
{
    /**
     * Handle the Angsuran "created" event.
     */
    public function created(Angsuran $angsuran): void
    {
        $saldo = Saldo::where('utama', true)->first();
        if ($saldo) {
            SaldoContext::set('ANGSURAN');
            KodeRefresnsiRiwayatSaldo::set($angsuran->nomor);
            $saldo->nominal += $angsuran->nominal;
            $saldo->save();
            SaldoContext::clear();
            KodeRefresnsiRiwayatSaldo::clear();
        }
    }

    /**
     * Handle the Angsuran "updated" event.
     */
    public function updated(Angsuran $angsuran): void
    {
        //
    }

    /**
     * Handle the Angsuran "deleted" event.
     */
    public function deleted(Angsuran $angsuran): void
    {
        //
    }

    /**
     * Handle the Angsuran "restored" event.
     */
    public function restored(Angsuran $angsuran): void
    {
        //
    }

    /**
     * Handle the Angsuran "force deleted" event.
     */
    public function forceDeleted(Angsuran $angsuran): void
    {
        //
    }
}

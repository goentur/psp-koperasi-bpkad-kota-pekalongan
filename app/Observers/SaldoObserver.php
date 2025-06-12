<?php

namespace App\Observers;

use App\Enums\RiwayatSaldoTipe;
use App\Models\RiwayatSaldo;
use App\Models\Saldo;
use App\Support\KodeRefresnsiRiwayatSaldo;
use App\Support\SaldoContext;

class SaldoObserver
{
    /**
     * Handle the Saldo "created" event.
     */
    public function created(Saldo $saldo): void
    {
        $tipe = RiwayatSaldoTipe::MASUK;
        RiwayatSaldo::create([
            'saldo_id' => $saldo->id,
            'kode_refrensi' => '',
            'nominal' => $saldo->nominal,
            'tanggal' => now(),
            'tipe' => $tipe,
            'keterangan' => 'SALDO AWAL',
        ]);
    }

    /**
     * Handle the Saldo "updated" event.
     */
    public function updated(Saldo $saldo): void
    {
        if ($saldo->isDirty('nominal')) {
            $originalNominal = $saldo->getOriginal('nominal');
            $newNominal = $saldo->nominal;
            $selisih = $newNominal - $originalNominal;
            $tipe = $selisih >= 0 ? RiwayatSaldoTipe::MASUK : RiwayatSaldoTipe::KELUAR;
            $saldoDari = in_array(SaldoContext::get(), ['TABUNGAN', 'PINJAMAN', 'ANGSURAN']) ? SaldoContext::get() : 'TIDAK DIKETAHUI';
            RiwayatSaldo::create([
                'saldo_id' => $saldo->id,
                'kode_refrensi' => $saldoDari . '-' . KodeRefresnsiRiwayatSaldo::get(),
                'nominal' => abs($selisih),
                'tanggal' => now(),
                'tipe' => $tipe,
                'keterangan' => 'SALDO ' . $tipe->value . ' DARI' . $saldoDari,
            ]);
        }
    }

    /**
     * Handle the Saldo "deleted" event.
     */
    public function deleted(Saldo $saldo): void
    {
        //
    }

    /**
     * Handle the Saldo "restored" event.
     */
    public function restored(Saldo $saldo): void
    {
        //
    }

    /**
     * Handle the Saldo "force deleted" event.
     */
    public function forceDeleted(Saldo $saldo): void
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Enums\RiwayatTabunganTipe;
use App\Models\RiwayatTabungan;
use App\Models\Saldo;
use App\Models\Tabungan;
use App\Support\KodeRefresnsiRiwayatSaldo;
use App\Support\SaldoContext;

class TabunganObserver
{
    /**
     * Handle the Tabungan "created" event.
     */
    public function created(Tabungan $tabungan): void
    {
        $saldo = Saldo::where('utama', true)->first();
        $kodeRefrensi = 'A-' . $tabungan->anggota_id . '-' . $tabungan->id;
        if ($saldo) {
            SaldoContext::set('TABUNGAN');
            KodeRefresnsiRiwayatSaldo::set($kodeRefrensi);
            $saldo->nominal += $tabungan->nominal;
            $saldo->save();
            SaldoContext::clear();
            KodeRefresnsiRiwayatSaldo::clear();
        }
        $tipe = RiwayatTabunganTipe::MASUK;
        RiwayatTabungan::create([
            'tabungan_id' => $tabungan->id,
            'nomor' => $kodeRefrensi,
            'nominal' => $tabungan->nominal,
            'tanggal' => now(),
            'tipe' => $tipe,
            'keterangan' => 'SALDO AWAL',
        ]);
    }

    /**
     * Handle the Tabungan "updated" event.
     */
    public function updated(Tabungan $tabungan): void
    {
        if ($tabungan->isDirty('nominal')) {
            $originalNominal = $tabungan->getOriginal('nominal');
            $newNominal = $tabungan->nominal;
            $selisih = $newNominal - $originalNominal;
            $saldo = Saldo::where('utama', true)->first();
            $kodeRefrensi = 'A-' . $tabungan->anggota_id . '-' . $tabungan->id;
            if ($saldo) {
                SaldoContext::set('TABUNGAN');
                KodeRefresnsiRiwayatSaldo::set($kodeRefrensi);
                $saldo->nominal += $selisih;
                $saldo->save();
                SaldoContext::clear();
                KodeRefresnsiRiwayatSaldo::clear();
            }
            $tipe = $selisih >= 0 ? RiwayatTabunganTipe::MASUK : RiwayatTabunganTipe::KELUAR;
            RiwayatTabungan::create([
                'tabungan_id' => $tabungan->id,
                'nomor' => $kodeRefrensi,
                'nominal' => abs($selisih),
                'tanggal' => now(),
                'tipe' => $tipe,
                'keterangan' => 'SALDO ' . $tipe->value,
            ]);
        }
    }

    /**
     * Handle the Tabungan "deleted" event.
     */
    public function deleted(Tabungan $tabungan): void
    {
        //
    }

    /**
     * Handle the Tabungan "restored" event.
     */
    public function restored(Tabungan $tabungan): void
    {
        //
    }

    /**
     * Handle the Tabungan "force deleted" event.
     */
    public function forceDeleted(Tabungan $tabungan): void
    {
        //
    }
}

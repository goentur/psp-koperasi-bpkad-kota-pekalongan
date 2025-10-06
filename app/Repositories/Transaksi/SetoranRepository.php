<?php

namespace App\Repositories\Transaksi;

use App\Enums\PinjamanStatus;
use App\Http\Resources\Transaksi\Setoran\PinjamanDataResource;
use App\Http\Resources\Transaksi\Setoran\TabunganDataResource;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Tabungan;

class SetoranRepository
{
    public function dataTabungan($request)
    {
        $tabungan = Tabungan::with('jenisTabungan')->select('id', 'jenis_tabungan_id', 'nominal')->where('anggota_id', $request->id)->latest()->get();
        return TabunganDataResource::collection($tabungan);
    }

    public function tabunganBaru($request)
    {
        $tabungan = Tabungan::select('id')->where(['anggota_id' => $request->anggota, 'jenis_tabungan_id' => $request->jenisTabungan])->first();

        if ($tabungan) {
            return back()->withErrors(['jenisTabungan' => 'Tabungan untuk jenis ini sudah ada.']);
        }

        return Tabungan::create([
            'anggota_id' => $request->anggota,
            'jenis_tabungan_id' => $request->jenisTabungan,
            'nominal' => $request->nominal,
            'tanggal_pendaftaran' => $request->tanggalPendaftaran,
        ]);
    }

    public function setoranBaru($request)
    {
        $tabungan = Tabungan::select('id', 'nominal', 'anggota_id')->where(['anggota_id' => $request->anggota, 'jenis_tabungan_id' => $request->jenisTabungan])->first();

        if ($tabungan) {
            return $tabungan->update([
                'nominal' => $tabungan->nominal + $request->nominal,
            ]);
        }
        return back()->withErrors(['nominal' => 'Data tabungan tidak diketahui.']);
    }

    public function dataPinjaman($request)
    {
        $pinjaman = Pinjaman::with('angsuran')->select('id', 'nominal', 'jangka_waktu')->where('anggota_id', $request->id)->latest()->get();
        return PinjamanDataResource::collection($pinjaman);
    }

    public function pinjamanBaru($request)
    {
        return Pinjaman::create([
            'anggota_id' => $request->anggota,
            'nominal' => $request->nominal,
            'jangka_waktu' => $request->jangkaWaktu,
            'tanggal_pendaftaran' => $request->tanggalPendaftaran,
            'tanggal_persetujuan' => $request->tanggalPersetujuan,
            'status' => PinjamanStatus::DISETUJUI
        ]);
    }

    public function angsuran($request)
    {
        $pinjaman = Pinjaman::find($request->pinjaman);
        $angsuran = $pinjaman->angsuran->count() + 1;
        return Angsuran::create([
            'pinjaman_id' => $request->pinjaman,
            'nomor' => 'A-' . $pinjaman->anggota_id . '-' . $request->pinjaman,
            'nominal' => $request->nominal,
            'tanggal_pembayaran' => $request->tanggalPembayaran,
            'bulan_ke' => $angsuran,
            'keterangan' => "Angsuran ke " . $angsuran . " untuk pinjaman dengan nominal " . $pinjaman->nominal . " pada tanggal " . $request->tanggalPembayaran,
        ]);
    }
}

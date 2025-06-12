<?php

namespace App\Repositories\Transaksi;

use App\Http\Resources\Transaksi\Setoran\PinjamanDataResource;
use App\Http\Resources\Transaksi\Setoran\TabunganDataResource;
use App\Models\Pinjaman;
use App\Models\Tabungan;

class SetoranRepository
{
    public function data($request)
    {
        $tabungan = Tabungan::with('jenisTabungan')->select('id', 'jenis_tabungan_id', 'nominal')->where('anggota_id', $request->id)->latest()->get();
        $pinjaman = Pinjaman::with('angsuran')->select('id', 'nominal', 'jangka_waktu')->where('anggota_id', $request->id)->latest()->get();
        return [
            'tabungan' => TabunganDataResource::collection($tabungan),
            'pinjaman' => PinjamanDataResource::collection($pinjaman),
        ];
    }
}

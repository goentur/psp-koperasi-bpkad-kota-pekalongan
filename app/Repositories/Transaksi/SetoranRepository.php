<?php

namespace App\Repositories\Transaksi;

use App\Enums\PinjamanStatus;
use App\Http\Resources\Transaksi\Setoran\PinjamanDataResource;
use App\Http\Resources\Transaksi\Setoran\TabunganDataResource;
use App\Models\Angsuran;
use App\Models\New\SPenganturanAkun;
use App\Models\New\TPinjaman;
use App\Models\New\TSimpanan;
use App\Models\New\TTransDetail;
use App\Models\New\TTransMaster;
use App\Models\New\TTransPinjaman;
use App\Models\New\TTransSimpanan;
use App\Models\Pinjaman;
use App\Models\Tabungan;
use App\Support\Facades\Helpers;
use Illuminate\Support\Facades\DB;

class SetoranRepository
{
    public function dataTabungan($request)
    {
        $tabungan = TSimpanan::with([
            'SProdSimpanan',
            'TTransSimpanan' => function ($query) {
                $query->select('id', 'simpanan_id', 'tgl_trans', 'nominal');
            },
            'TTransTarik' => function ($query) {
                $query->select('id', 'simpanan_id', 'tgl_trans', 'nominal');
            }
        ])
            ->select('id', 's_prod_simpanan_id', 'no_rekening', 'anggota_id')
            ->where('anggota_id', $request->id)
            ->latest()
            ->get();

        return TabunganDataResource::collection($tabungan);
    }

    public function tabunganBaru($request)
    {
        $tabungan = Tabungan::select('id')->where(['anggota_id' => $request->anggota, 'jenis_tabungan_id' => $request->jenisTabungan])->first();

        if ($tabungan) {
            return back()->withErrors(['jenisTabungan' => 'Tabungan untuk jenis ini sudah ada.']);
        }
        return null;
        return Tabungan::create([
            'anggota_id' => $request->anggota,
            'jenis_tabungan_id' => $request->jenisTabungan,
            'nominal' => $request->nominal,
            'tanggal_pendaftaran' => $request->tanggalPendaftaran,
        ]);
    }

    public function setoranAtauTarik($request)
    {
        try {
            DB::transaction(function () use ($request) {
                $tanggal = $request->tanggal
                    ? \Carbon\Carbon::parse($request->tanggal)->timezone('Asia/Jakarta')
                    : now()->timezone('Asia/Jakarta');

                $simpanan = TSimpanan::findOrFail($request->jenisTabungan);

                $pengaturanAkun = SPenganturanAkun::where([
                    'kode_trans' => $request->tipe === 'setoran' ? '01' : '02',
                    's_prod_id' => $simpanan->s_prod_simpanan_id
                ])->get();

                if ($pengaturanAkun->isEmpty()) {
                    throw new \Exception('Pengaturan akun tidak ditemukan untuk produk simpanan ini.');
                }
                $keterangan = $request->tipe === 'setoran' ? 'Setoran  tanggal ' . $tanggal : 'Tarik  tanggal ' . $tanggal;
                $TTransSimpanan = TTransSimpanan::create([
                    'simpanan_id' => $request->jenisTabungan,
                    'kode_trans' => $request->tipe === 'setoran' ? '01' : '02',
                    'tgl_trans' => $tanggal,
                    'jenis_trans' => $request->tipe === 'setoran' ? '01' : '02',
                    'nominal' => $request->nominal,
                    'keterangan' => $keterangan,
                ]);

                $TransMaster = TTransMaster::create([
                    'module_source' => 'simpanan',
                    'trans_id' => $TTransSimpanan->id,
                    'tgl_trans' => $tanggal,
                    'keterangan' => $keterangan,
                ]);

                foreach ($pengaturanAkun as $value) {
                    // Debet
                    TTransDetail::create([
                        'trans_master_id' => $TransMaster->id,
                        'akun_id' => $value->debet_akun_id,
                        'debet' => $request->nominal,
                        'kredit' => 0,
                        'keterangan' => $keterangan,
                    ]);

                    // Kredit
                    TTransDetail::create([
                        'trans_master_id' => $TransMaster->id,
                        'akun_id' => $value->kredit_akun_id,
                        'debet' => 0,
                        'kredit' => $request->nominal,
                        'keterangan' => $keterangan,
                    ]);
                }
            });
            return response()->json(['message' => 'Transaksi berhasil disimpan.'], 200);
        } catch (\Exception $e) {
            // Gagal
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function dataPinjaman($request)
    {
        $pinjaman = TPinjaman::with('TTransPinjaman')->select('id', 'plafon', 'jangka_waktu')->where('anggota_id', $request->id)->latest()->get();
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
        try {
            DB::transaction(function () use ($request) {
                $tanggal = $request->tanggalPembayaran
                    ? \Carbon\Carbon::parse($request->tanggalPembayaran)->timezone('Asia/Jakarta')
                    : now()->timezone('Asia/Jakarta');

                $pinjaman = TPinjaman::findOrFail($request->pinjaman);

                $pengaturanAkun = SPenganturanAkun::where([
                    'kode_trans' => '21',
                    'module_source' => 'pinjaman',
                    's_prod_id' => $pinjaman->s_prod_pinjaman_id
                ])->get();

                if ($pengaturanAkun->isEmpty()) {
                    throw new \Exception('Pengaturan akun tidak ditemukan untuk produk pinjaman ini.');
                }

                $TTransPinjaman = TTransPinjaman::create([
                    'pinjaman_id' => $request->pinjaman,
                    'kode_trans' => '21',
                    'tgl_trans' => $tanggal,
                    'jenis_trans' => '01',
                    'nominal' => $request->nominal,
                    'keterangan' => 'Angsuran tanggal ' . $tanggal,
                ]);

                $TransMaster = TTransMaster::create([
                    'module_source' => 'pinjaman',
                    'trans_id' => $TTransPinjaman->id,
                    'tgl_trans' => $tanggal,
                    'keterangan' => 'Angsuran tanggal ' . $tanggal,
                ]);

                foreach ($pengaturanAkun as $value) {
                    // Debet
                    TTransDetail::create([
                        'trans_master_id' => $TransMaster->id,
                        'akun_id' => $value->debet_akun_id,
                        'debet' => $request->nominal,
                        'kredit' => 0,
                        'keterangan' => 'Angsuran tanggal ' . $tanggal,
                    ]);

                    // Kredit
                    TTransDetail::create([
                        'trans_master_id' => $TransMaster->id,
                        'akun_id' => $value->kredit_akun_id,
                        'debet' => 0,
                        'kredit' => $request->nominal,
                        'keterangan' => 'Angsuran tanggal ' . $tanggal,
                    ]);
                }
            });
            return response()->json(['message' => 'Transaksi berhasil disimpan.'], 200);
        } catch (\Exception $e) {
            // Gagal
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

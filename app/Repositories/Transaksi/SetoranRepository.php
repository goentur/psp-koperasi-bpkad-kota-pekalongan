<?php

namespace App\Repositories\Transaksi;

use App\Enums\PinjamanStatus;
use App\Http\Resources\Transaksi\Setoran\PinjamanDataResource;
use App\Http\Resources\Transaksi\Setoran\TabunganDataResource;
use App\Models\Angsuran;
use App\Models\New\SPenganturanAkun;
use App\Models\New\TSimpanan;
use App\Models\New\TTransDetail;
use App\Models\New\TTransMaster;
use App\Models\New\TTransSimpanan;
use App\Models\Pinjaman;
use App\Models\Tabungan;
use App\Support\Facades\Helpers;
use Illuminate\Support\Facades\DB;

class SetoranRepository
{
    public function dataTabungan($request)
    {
        $tabungan = TSimpanan::with(['SProdSimpanan', 'TTransSimpanan' => function ($query) {
            $query->select('id', 'simpanan_id', 'tgl_trans', 'nominal');
        }])
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

    public function setoranBaru($request)
    {
        try {
            DB::transaction(function () use ($request) {
                $tanggal = $request->tanggal
                    ? \Carbon\Carbon::parse($request->tanggal)->timezone('Asia/Jakarta')
                    : now()->timezone('Asia/Jakarta');

                $simpanan = TSimpanan::findOrFail($request->jenisTabungan);

                $pengaturanAkun = SPenganturanAkun::where([
                    'kode_trans' => '01',
                    's_prod_id' => $simpanan->s_prod_simpanan_id
                ])->get();

                if ($pengaturanAkun->isEmpty()) {
                    throw new \Exception('Pengaturan akun tidak ditemukan untuk produk simpanan ini.');
                }

                $TTransSimpanan = TTransSimpanan::create([
                    'simpanan_id' => $request->jenisTabungan,
                    'kode_trans' => '01',
                    'tgl_trans' => $tanggal,
                    'jenis_trans' => '01',
                    'nominal' => $request->nominal,
                    'keterangan' => 'Setoran tanggal ' . $tanggal,
                ]);

                $TransMaster = TTransMaster::create([
                    'module_source' => 'simpanan',
                    'trans_id' => $TTransSimpanan->id,
                    'tgl_trans' => $tanggal,
                    'keterangan' => 'Setoran tanggal ' . $tanggal,
                ]);

                foreach ($pengaturanAkun as $value) {
                    // Debet
                    TTransDetail::create([
                        'trans_master_id' => $TransMaster->id,
                        'akun_id' => $value->debet_akun_id,
                        'debet' => $request->nominal,
                        'kredit' => 0,
                        'keterangan' => 'Setoran tanggal ' . $tanggal,
                    ]);

                    // Kredit
                    TTransDetail::create([
                        'trans_master_id' => $TransMaster->id,
                        'akun_id' => $value->kredit_akun_id,
                        'debet' => 0,
                        'kredit' => $request->nominal,
                        'keterangan' => 'Setoran tanggal ' . $tanggal,
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
        $tanggal = $request->tanggalPembayaran
            ? \Carbon\Carbon::parse($request->tanggalPembayaran)->timezone('Asia/Jakarta')
            : now()->timezone('Asia/Jakarta');
        return Angsuran::create([
            'pinjaman_id' => $request->pinjaman,
            'nomor' => 'A-' . $pinjaman->anggota_id . '-' . $request->pinjaman,
            'nominal' => $request->nominal,
            'tanggal_pembayaran' => $tanggal,
            'bulan_ke' => $angsuran,
            'keterangan' => "Angsuran ke " . $angsuran . " untuk pinjaman dengan nominal " . $pinjaman->nominal . " pada tanggal " . $tanggal,
        ]);
    }
}

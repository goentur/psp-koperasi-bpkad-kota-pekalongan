<?php

namespace App\Repositories\Transaksi;

use App\Http\Resources\Transaksi\Persetujuan\AngsuranTabunganResource;
use App\Http\Resources\Transaksi\Persetujuan\PersetujuanTabunganResource;
use App\Models\New\TTransPinjaman;
use App\Models\New\TTransSimpanan;
use Illuminate\Support\Facades\DB;

class PersetujuanRepository
{
    public function dataTransaksiSimpanan($request)
    {
        $query = TTransSimpanan::with('TSimpanan', 'TSimpanan.SAnggota', 'TSimpanan.SProdSimpanan')
            ->select('id', 'simpanan_id', 'tgl_trans', 'jenis_trans', 'nominal', 'keterangan')
            ->where('status', 'menunggu')
            ->when($request->anggota, function ($q) use ($request) {
                $q->whereHas('TSimpanan', function ($query) use ($request) {
                    $query->where('anggota_id', $request->anggota);
                });
            })
            ->when($request->simpanan, function ($q) use ($request) {
                $q->whereHas('TSimpanan', function ($query) use ($request) {
                    $query->where('s_prod_simpanan_id', $request->simpanan);
                });
            })
            ->orderBy('tgl_trans', 'asc');
        $result = PersetujuanTabunganResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
        return $result['meta'] + ['data' => $result['data']];
    }

    public function terimaTransaksiSimpanan($request)
    {
        try {
            DB::beginTransaction();
            $model = TTransSimpanan::find($request->id);
            $model->update([
                'status' => 'terima'
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function dataTransaksiAngsuran($request)
    {
        $query = TTransPinjaman::with('TPinjaman', 'TPinjaman.SAnggota')
            ->select('id', 'pinjaman_id', 'tgl_trans', 'jenis_trans', 'nominal', 'keterangan')
            ->where('status', 'menunggu')
            ->when($request->anggota, function ($q) use ($request) {
                $q->whereHas('TPinjaman', function ($query) use ($request) {
                    $query->where('anggota_id', $request->anggota);
                });
            })
            ->orderBy('tgl_trans', 'asc');
        $result = AngsuranTabunganResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
        return $result['meta'] + ['data' => $result['data']];
    }

    public function terimaTransaksiAngsuran($request)
    {
        $model = TTransPinjaman::find($request->id);
        try {
            DB::beginTransaction();
            $model->update([
                'status' => 'terima'
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

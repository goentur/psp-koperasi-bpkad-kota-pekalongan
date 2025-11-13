<?php

namespace App\Repositories\Master;

use App\Http\Resources\Master\Anggota\SelectOptionResource;
use App\Models\Anggota;
use App\Models\New\SAnggota;
use App\Models\Tabungan;
use Illuminate\Support\Facades\DB;

class AnggotaRepository
{
    public function __construct(protected Anggota $model) {}

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $anggota = $this->model->create([
                'satuan_kerja_id' => $request->satuanKerja,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'status_kepegawaian' => $request->statusKepegawaian,
                'tanggal_pendaftaran' => $request->tanggal,
            ]);
            Tabungan::create([
                'anggota_id' => $anggota->id,
                'jenis_tabungan_id' => $request->jenisTabungan,
                'nominal' => $request->nominal,
                'tanggal_pendaftaran' => $request->tanggal,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Anggota $anggota, $request)
    {
        try {
            DB::beginTransaction();
            $anggota->update([
                'satuan_kerja_id' => $request->satuanKerja,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'status_kepegawaian' => $request->statusKepegawaian,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Anggota $anggota)
    {
        try {
            DB::beginTransaction();
            $anggota->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function data($request)
    {
        return $this->model::with('satuanKerja')->select('id', 'nik', 'nama', 'status_kepegawaian', 'satuan_kerja_id')
            ->when($request->search, function ($q) use ($request) {
                $q->whereLike('nik', "%{$request->search}%")
                    ->orWhereLike('nama', "%{$request->search}%")
                    ->orWhereLike('status_kepegawaian', "%{$request->search}%")
                    ->orWhereHas('satuanKerja', fn($q) => $q->whereLike('nama', "%{$request->search}%"));
            })
            ->latest()->paginate($request->perPage ?? 25);
    }

    public function list($request)
    {
        $query = SAnggota::with('satuanKerja')->select('id', 'nama', 'bidang');
        if ($request->id && $request->search == '') {
            $data = $query->where('id', $request->id)->get();
        } elseif ($request->search) {
            $data = $query->whereLike('nama', "%{$request->search}%")
                ->latest()
                ->limit(9)
                ->get();
        } else {
            $data = collect();
        }
        return SelectOptionResource::collection($data);
    }
}

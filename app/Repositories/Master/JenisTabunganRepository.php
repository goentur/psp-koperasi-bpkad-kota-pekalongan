<?php

namespace App\Repositories\Master;

use App\Http\Resources\Common\SelectOptionResource;
use App\Models\JenisTabungan;
use Illuminate\Support\Facades\DB;

class JenisTabunganRepository
{
    public function __construct(protected JenisTabungan $model) {}

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $this->model->create([
                'nama' => $request->nama,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(JenisTabungan $jenisTabungan, $request)
    {
        try {
            DB::beginTransaction();
            $jenisTabungan->update([
                'nama' => $request->nama,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(JenisTabungan $jenisTabungan)
    {
        try {
            DB::beginTransaction();
            $jenisTabungan->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function data($request)
    {
        return $this->model::select('id', 'nama')
            ->when($request->search, function ($q) use ($request) {
                $q->whereLike('nama', "%{$request->search}%");
            })
            ->latest()->paginate($request->perPage ?? 25);
    }

    public function list($request)
    {
        $query = $this->model::select('id', 'nama');
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

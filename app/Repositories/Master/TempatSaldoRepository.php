<?php

namespace App\Repositories\Master;

use App\Http\Resources\Common\SelectOptionResource;
use App\Models\TempatSaldo;
use Illuminate\Support\Facades\DB;

class TempatSaldoRepository
{
    public function __construct(protected TempatSaldo $model) {}

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

    public function update(TempatSaldo $tempatSaldo, $request)
    {
        try {
            DB::beginTransaction();
            $tempatSaldo->update([
                'nama' => $request->nama,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(TempatSaldo $tempatSaldo)
    {
        try {
            DB::beginTransaction();
            $tempatSaldo->delete();
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

    public function list()
    {
        return SelectOptionResource::collection($this->model::select('id', 'nama')->get());
    }
}

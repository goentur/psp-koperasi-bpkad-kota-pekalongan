<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Common\ListRequest;
use App\Http\Requests\Master\SatuanKerja\StoreRequest;
use App\Http\Requests\Master\SatuanKerja\UpdateRequest;
use App\Models\SatuanKerja;
use App\Repositories\Master\SatuanKerjaRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SatuanKerjaController extends Controller implements HasMiddleware
{
    protected SatuanKerjaRepository $repository;

    public function __construct(SatuanKerjaRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:satuan-kerja-index', only: ['index', 'data']),
            new Middleware('can:satuan-kerja-create', only: ['store']),
            new Middleware('can:satuan-kerja-update', only: ['update']),
            new Middleware('can:satuan-kerja-delete', only: ['destroy']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('satuan-kerja-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('satuan-kerja-create'),
                'update' => $user->can('satuan-kerja-update'),
                'delete' => $user->can('satuan-kerja-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('master/satuan-kerja/index', compact("gate"));
    }

    public function create()
    {
        abort(404);
    }

    public function store(StoreRequest $request)
    {
        $this->repository->store($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(UpdateRequest $request, SatuanKerja $satuanKerja)
    {
        $this->repository->update($satuanKerja, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(SatuanKerja $satuanKerja)
    {
        $this->repository->delete($satuanKerja);
        back()->with('success', 'Data berhasil dihapus');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
    }

    public function list(ListRequest $request)
    {
        return response()->json($this->repository->list($request));
    }
}

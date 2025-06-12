<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Common\ListRequest;
use App\Http\Requests\Master\Anggota\StoreRequest;
use App\Http\Requests\Master\Anggota\UpdateRequest;
use App\Models\Anggota;
use App\Repositories\Master\AnggotaRepository;
use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnggotaController extends Controller implements HasMiddleware
{
    protected AnggotaRepository $repository;

    public function __construct(AnggotaRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:anggota-index', only: ['index', 'data']),
            new Middleware('can:anggota-create', only: ['store']),
            new Middleware('can:anggota-update', only: ['update']),
            new Middleware('can:anggota-delete', only: ['destroy']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('anggota-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('anggota-create'),
                'update' => $user->can('anggota-update'),
                'delete' => $user->can('anggota-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('master/anggota/index', compact("gate"));
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

    public function update(UpdateRequest $request)
    {
        $this->repository->update(Anggota::find($request->id), $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(Request $request)
    {
        $this->repository->delete(Anggota::find($request->id));
        back()->with('success', 'Data berhasil dihapus');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
    }

    public function list(ListRequest $request)
    {
        return response()->json($this->repository->list($request), 200);
    }
}

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Common\ListRequest;
use App\Http\Requests\Master\JenisTabungan\StoreRequest;
use App\Http\Requests\Master\JenisTabungan\UpdateRequest;
use App\Models\JenisTabungan;
use App\Repositories\Master\JenisTabunganRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class JenisTabunganController extends Controller implements HasMiddleware
{
    protected JenisTabunganRepository $repository;

    public function __construct(JenisTabunganRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:jenis-tabungan-index', only: ['index', 'data']),
            new Middleware('can:jenis-tabungan-create', only: ['store']),
            new Middleware('can:jenis-tabungan-update', only: ['update']),
            new Middleware('can:jenis-tabungan-delete', only: ['destroy']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('jenis-tabungan-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('jenis-tabungan-create'),
                'update' => $user->can('jenis-tabungan-update'),
                'delete' => $user->can('jenis-tabungan-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('master/jenis-tabungan/index', compact("gate"));
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

    public function update(UpdateRequest $request, JenisTabungan $jenisTabungan)
    {
        $this->repository->update($jenisTabungan, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(JenisTabungan $jenisTabungan)
    {
        $this->repository->delete($jenisTabungan);
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

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Master\TempatSaldo\StoreRequest;
use App\Http\Requests\Master\TempatSaldo\UpdateRequest;
use App\Models\TempatSaldo;
use App\Repositories\Master\TempatSaldoRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TempatSaldoController extends Controller implements HasMiddleware
{
    protected TempatSaldoRepository $repository;

    public function __construct(TempatSaldoRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:tempat-saldo-index', only: ['index', 'data']),
            new Middleware('can:tempat-saldo-create', only: ['store']),
            new Middleware('can:tempat-saldo-update', only: ['update']),
            new Middleware('can:tempat-saldo-delete', only: ['destroy']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('tempat-saldo-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('tempat-saldo-create'),
                'update' => $user->can('tempat-saldo-update'),
                'delete' => $user->can('tempat-saldo-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('master/tempat-saldo/index', compact("gate"));
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

    public function update(UpdateRequest $request, TempatSaldo $tempatSaldo)
    {
        $this->repository->update($tempatSaldo, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(TempatSaldo $tempatSaldo)
    {
        $this->repository->delete($tempatSaldo);
        back()->with('success', 'Data berhasil dihapus');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
    }

    public function list()
    {
        return response()->json($this->repository->list(), 200);
    }
}

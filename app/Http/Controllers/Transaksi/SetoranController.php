<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\Setoran\DataRequest;
use App\Repositories\Transaksi\SetoranRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SetoranController extends Controller implements HasMiddleware
{
    public function __construct(protected SetoranRepository $repository) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:transaksi-setoran-index', only: ['index', 'data']),
            new Middleware('can:transaksi-setoran-anggota-create', only: ['save']),
            new Middleware('can:transaksi-setoran-create', only: ['store']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('transaksi-setoran-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('transaksi-setoran-anggota-create'),
                'update' => $user->can('transaksi-setoran-create'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('transaksi/setoran/index', compact("gate"));
    }

    // public function save(StoreRequest $request)
    // {
    //     $this->repository->store($request);
    //     back()->with('success', 'Data berhasil ditambahkan');
    // }

    // public function store(StoreRequest $request)
    // {
    //     $this->repository->store($request);
    //     back()->with('success', 'Data berhasil ditambahkan');
    // }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
    }
}

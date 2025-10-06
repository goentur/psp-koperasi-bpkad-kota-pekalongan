<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\Setoran\AngsuranRequest;
use App\Http\Requests\Transaksi\Setoran\DataRequest;
use App\Http\Requests\Transaksi\Setoran\PinjamanBaruRequest;
use App\Http\Requests\Transaksi\Setoran\SetoranBaruRequest;
use App\Http\Requests\Transaksi\Setoran\TabunganBaruRequest;
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

    public function dataTabungan(DataRequest $request)
    {
        return response()->json($this->repository->dataTabungan($request));
    }
    public function tabunganBaru(TabunganBaruRequest $request)
    {
        $this->repository->tabunganBaru($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }
    public function setoranBaru(SetoranBaruRequest $request)
    {
        $this->repository->setoranBaru($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }
    public function dataPinjaman(DataRequest $request)
    {
        return response()->json($this->repository->dataPinjaman($request));
    }
    public function pinjamanBaru(PinjamanBaruRequest $request)
    {
        $this->repository->pinjamanBaru($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }
    public function angsuran(AngsuranRequest $request)
    {
        $this->repository->angsuran($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }
}

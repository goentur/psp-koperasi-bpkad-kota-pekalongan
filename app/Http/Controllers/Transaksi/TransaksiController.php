<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\Persetujuan\DataPersetujuanAngsuranRequest;
use App\Http\Requests\Transaksi\Persetujuan\DataPersetujuanTabunganRequest;
use App\Http\Requests\Transaksi\Persetujuan\TerimaPersetujuanAngsuranRequest;
use App\Http\Requests\Transaksi\Persetujuan\TerimaPersetujuanTabunganRequest;
use App\Http\Requests\Transaksi\Setoran\AngsuranRequest;
use App\Http\Requests\Transaksi\Setoran\DataRequest;
use App\Http\Requests\Transaksi\Setoran\PinjamanBaruRequest;
use App\Http\Requests\Transaksi\Setoran\SetoranAtauTarikRequest;
use App\Http\Requests\Transaksi\Setoran\TabunganBaruRequest;
use App\Models\New\SProdSimpanan;
use App\Repositories\Transaksi\PersetujuanRepository;
use App\Repositories\Transaksi\SetoranRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TransaksiController extends Controller implements HasMiddleware
{
    public function __construct(
        protected SetoranRepository $repository,
        protected PersetujuanRepository $persetujuan_repository
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:transaksi-index', only: ['index', 'data']),
            new Middleware('can:transaksi-anggota-create', only: ['save']),
            new Middleware('can:transaksi-create', only: ['store']),
            new Middleware('can:transaksi-persetujuan', only: ['persetujuan']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('transaksi-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('transaksi-anggota-create'),
                'update' => $user->can('transaksi-create'),
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
    public function setoranAtauTarik(SetoranAtauTarikRequest $request)
    {
        $this->repository->setoranAtauTarik($request);
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

    public function persetujuanSimpanan()
    {
        $simpanans = SProdSimpanan::orderBy('id')->get()->map(function ($simpanan) {
            $simpanan->label = $simpanan->nama;
            $simpanan->value = $simpanan->id;
            return $simpanan;
        });
        return inertia('transaksi/persetujuan/tabungan/index', compact('simpanans'));
    }

    public function dataTransaksiSimpanan(DataPersetujuanTabunganRequest $request)
    {
        return response()->json($this->persetujuan_repository->dataTransaksiSimpanan($request));
    }

    public function terimaTransaksiSimpanan(TerimaPersetujuanTabunganRequest $request)
    {
        $this->persetujuan_repository->terimaTransaksiSimpanan($request);
        back()->with('success', 'Data berhasil diterima');
    }

    public function persetujuanAngsuran()
    {
        return inertia('transaksi/persetujuan/angsuran/index');
    }

    public function dataTransaksiAngsuran(DataPersetujuanAngsuranRequest $request)
    {
        return response()->json($this->persetujuan_repository->dataTransaksiAngsuran($request));
    }

    public function terimaTransaksiAngsuran(TerimaPersetujuanAngsuranRequest $request)
    {
        $this->persetujuan_repository->terimaTransaksiAngsuran($request);
        back()->with('success', 'Data berhasil diterima');
    }
}

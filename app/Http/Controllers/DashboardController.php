<?php

namespace App\Http\Controllers;

use App\Models\New\SAnggota;
use App\Models\New\TTransDetail;
use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('dashboard');
    }
    public function dataAnggotaDashboard()
    {
        $anggotaDenganTotalSimpanan = SAnggota::with(['TSimpanan.TTransSimpanan', 'TPinjaman', 'TPinjaman.TTransPinjaman', 'TPinjaman.TTransPinjamanSaldoAwal', 'satuanKerja']) // Eager load relasi
            ->orderBy('bidang')
            ->orderBy('nama')
            ->get()
            ->map(function ($anggota) {
                $totalNominal = 0;
                foreach ($anggota->TSimpanan as $simpanan) {
                    $totalNominal += $simpanan->TTransSimpanan->sum('nominal');
                }
                $totalNominalPinjaman = 0;
                $totalNominalPinjamanAngsuran = 0;
                foreach ($anggota->TPinjaman as $simpanan) {
                    $totalNominalPinjamanAngsuran += $simpanan->TTransPinjaman->sum('nominal');
                    $totalNominalPinjaman = $simpanan->TTransPinjamanSaldoAwal->sum('nominal');
                }
                $anggota->total_simpanan_all = Helpers::ribuan($totalNominal);
                $anggota->total_pinjaman_all = Helpers::ribuan($totalNominalPinjaman - $totalNominalPinjamanAngsuran);
                return $anggota;
            });
        return response()->json($anggotaDenganTotalSimpanan);
    }
    public function dataDashboard()
    {
        $kas = TTransDetail::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('akun_id', 101)->first();
        $bank = TTransDetail::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('akun_id', 102)->first();
        $pinjaman = TTransDetail::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('akun_id', 111)->first();
        $simpanan = TTransDetail::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->whereIn('akun_id', [201, 202, 203])->first();
        $data = [
            'kas' => Helpers::ribuan($kas->debet - $kas->kredit),
            'bank' => Helpers::ribuan($bank->debet - $bank->kredit),
            'pinjaman' => Helpers::ribuan($pinjaman->debet - $pinjaman->kredit),
            'simpanan' => Helpers::ribuan($simpanan->kredit - $simpanan->debet),
        ];

        return response()->json($data);
    }
}

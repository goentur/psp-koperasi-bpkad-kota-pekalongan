<?php

namespace App\Http\Controllers;

use App\Models\New\SAnggota;
use App\Models\New\SProdSimpanan;
use App\Models\New\TTransDetail;
use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $prodSimpanan = SProdSimpanan::orderBy('id')->get();
        return inertia('dashboard', compact('prodSimpanan'));
    }
    public function dataAnggotaDashboard()
    {
        $anggotaDenganTotalSimpanan = SAnggota::with([
            'TSimpanan.TTransSimpanan',
            'TSimpanan.TTransTarik',
            'TPinjaman',
            'TPinjaman.TTransPinjaman',
            'satuanKerja'
        ])
            ->orderBy('bidang')
            ->orderBy('nama')
            ->get()
            ->map(function ($anggota) {
                $groupedSimpanan = $anggota->TSimpanan->groupBy('s_prod_simpanan_id');

                $detailSimpanan = [];
                $grandTotalSimpanan = 0;
                $grandTotalTarik = 0;

                foreach ($groupedSimpanan as $jenis => $simpananItems) {
                    $totalSimpananPerJenis = 0;
                    $totalTarikPerJenis = 0;
                    $tanggalTerakhirSetor = null;

                    foreach ($simpananItems as $simpanan) {
                        $masuk = $simpanan->TTransSimpanan->sum('nominal');
                        $keluar = $simpanan->TTransTarik->sum('nominal');

                        $totalSimpananPerJenis += $masuk;
                        $totalTarikPerJenis += $keluar;

                        $allTransactions = $simpanan->TTransSimpanan->merge($simpanan->TTransTarik);

                        if ($allTransactions->isNotEmpty()) {
                            $latestDate = $allTransactions->max('tgl_trans');
                            if (!$tanggalTerakhirSetor || $latestDate > $tanggalTerakhirSetor) {
                                $tanggalTerakhirSetor = $latestDate;
                            }
                        }
                    }

                    $saldoPerJenis = $totalSimpananPerJenis - $totalTarikPerJenis;
                    $tanggalFormatted = $tanggalTerakhirSetor
                        ? \Carbon\Carbon::parse($tanggalTerakhirSetor)->format('d M Y')
                        : '-';

                    $detailSimpanan[] = [
                        'jenis' => $jenis,
                        'tanggal' => $tanggalFormatted,
                        'masuk' => Helpers::ribuan($totalSimpananPerJenis),
                        'tarik' => Helpers::ribuan($totalTarikPerJenis),
                        'saldo' => Helpers::ribuan($saldoPerJenis),
                    ];

                    $grandTotalSimpanan += $totalSimpananPerJenis;
                    $grandTotalTarik += $totalTarikPerJenis;
                }

                $totalNominalPinjaman = $anggota->TPinjaman->sum('plafon');
                $totalNominalPinjamanAngsuran = 0;
                $tanggalTerakhirSetorPinjaman = null;
                foreach ($anggota->TPinjaman as $pinjaman) {
                    $totalNominalPinjamanAngsuran += $pinjaman->TTransPinjaman->sum('nominal');
                    $tanggalTerakhirSetorPinjaman = $pinjaman->TTransPinjaman->max('tgl_trans');
                }

                $anggota->detail_simpanan = $detailSimpanan;
                $anggota->total_simpanan_all = Helpers::ribuan($grandTotalSimpanan - $grandTotalTarik);
                $anggota->total_pinjaman_all = Helpers::ribuan($totalNominalPinjaman - $totalNominalPinjamanAngsuran);
                $anggota->tanggal_terakhir_pinjaman = $tanggalTerakhirSetorPinjaman ? \Carbon\Carbon::parse($tanggalTerakhirSetorPinjaman)->format('d M Y') : '-';

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

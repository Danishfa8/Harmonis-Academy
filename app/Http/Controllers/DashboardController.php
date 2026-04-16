<?php

namespace App\Http\Controllers;

use App\Models\PaketKursus;
use App\Models\Pembayaran;
use App\Models\Peserta;
use App\Models\PenilaianSkill;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // === PESERTA DASHBOARD ===
        if ($user->role === 'peserta') {
            return redirect()->route('peserta.dashboard');
        }

        // === ADMIN DASHBOARD ===
        $totalPeserta = Peserta::count();
        $pesertaAktif = Peserta::where('status_pendaftaran', 'aktif')->count();
        $pesertaSelesai = Peserta::where('status_pendaftaran', 'selesai')->count();
        $paketAktif = PaketKursus::where('status', 'aktif')->count();

        $year = Carbon::now()->year;
        $bulanLabels = [];
        $bulanData = [];
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        for ($m = 1; $m <= 12; $m++) {
            $bulanLabels[] = $namaBulan[$m - 1];
            $bulanData[] = (int) Pembayaran::where('status', 'valid')
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $m)
                ->sum('nominal');
        }
        $totalPendapatan = array_sum($bulanData);

        $pesertaTerbaru = Peserta::with('paketKursus')
            ->latest('tgl_masuk')
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'totalPeserta', 'pesertaAktif', 'pesertaSelesai', 'paketAktif',
            'bulanLabels', 'bulanData', 'totalPendapatan', 'year',
            'pesertaTerbaru'
        ));
    }
}

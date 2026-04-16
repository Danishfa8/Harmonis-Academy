<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peserta;

class DashboardPesertaController extends Controller
{
    private function getPeserta()
    {
        return Peserta::with(['paketKursus', 'penilaianSkill', 'pembayaran'])
            ->where('id', Auth::user()->peserta_id)
            ->first();
    }

    public function dashboard()
    {
        $peserta = $this->getPeserta();
        
        // Calculate total pembayaran valid
        $totalHarga = $peserta && $peserta->paketKursus ? $peserta->paketKursus->harga : 0;
        $sudahBayar = $peserta ? $peserta->pembayaran()->where('status', 'valid')->sum('nominal') : 0;
        $sisa = max(0, $totalHarga - $sudahBayar);
        
        $progressB = $totalHarga > 0 ? min(100, round(($sudahBayar / $totalHarga) * 100)) : 0;

        return view('peserta-dashboard.index', compact('peserta', 'totalHarga', 'sudahBayar', 'sisa', 'progressB'));
    }

    public function nilai()
    {
        $peserta = $this->getPeserta();
        return view('peserta-dashboard.nilai', compact('peserta'));
    }

    public function pembayaran()
    {
        $peserta = $this->getPeserta();
        $totalValid = $peserta ? $peserta->pembayaran()->where('status', 'valid')->sum('nominal') : 0;
        $riwayat = $peserta ? $peserta->pembayaran()->orderBy('tanggal', 'desc')->get() : collect();

        return view('peserta-dashboard.pembayaran', compact('peserta', 'totalValid', 'riwayat'));
    }
}

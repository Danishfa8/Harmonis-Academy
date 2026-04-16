<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $pesertaId = $request->get('peserta_id');
        $daftarPeserta = Peserta::orderBy('nama')->get();

        $pembayaran = Pembayaran::with('peserta.paketKursus')
            ->when($pesertaId, fn($q) => $q->where('peserta_id', $pesertaId))
            ->latest('tanggal')->paginate(10);

        // Build complete 12-month chart data
        $bulanLabels = [];
        $bulanData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $date->locale('id')->isoFormat('MMM Y');
            $total = Pembayaran::where('status', 'valid')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('nominal');
            $bulanData[] = (float) $total;
        }

        return view('riwayat-pembayaran.index', compact('pembayaran', 'daftarPeserta', 'pesertaId', 'bulanLabels', 'bulanData'));
    }
}

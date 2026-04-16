<?php

namespace App\Http\Controllers;

use App\Models\PaketKursus;
use App\Models\Pembayaran;
use App\Models\Peserta;
use App\Models\PenilaianSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendapatanExport;

class LaporanController extends Controller
{
    public function peserta(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $peserta = Peserta::with('paketKursus')
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status_pendaftaran', $status))
            ->latest()->paginate(15);

        return view('laporan.peserta', compact('peserta', 'search', 'status'));
    }

    public function exportPdfPeserta(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $peserta = Peserta::with('paketKursus')
            ->when($search, fn($q) => $q->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            }))
            ->when($status, fn($q) => $q->where('status_pendaftaran', $status))
            ->latest()
            ->get();

        $pdf = Pdf::loadView('laporan.pdf_peserta', compact('peserta', 'search', 'status'))
            ->setPaper('a4', 'landscape');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-peserta.pdf"',
        ]);
    }

    public function pendapatan(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $monthlyRevenue = Pembayaran::select(DB::raw("MONTH(tanggal) as bulan"), DB::raw("SUM(nominal) as total"))
            ->where('status', 'valid')->whereYear('tanggal', $year)->groupBy('bulan')->orderBy('bulan')->get()->keyBy('bulan');

        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = Carbon::create()->month($i)->locale('id')->isoFormat('MMM');
            $chartData[] = (float) ($monthlyRevenue->get($i)?->total ?? 0);
        }

        $totalBulanan = Pembayaran::where('status', 'valid')->whereYear('tanggal', $year)->whereMonth('tanggal', Carbon::now()->month)->sum('nominal');
        $totalTahunan = Pembayaran::where('status', 'valid')->whereYear('tanggal', $year)->sum('nominal');

        $revenueByPackage = Pembayaran::join('peserta', 'pembayaran.peserta_id', '=', 'peserta.id')
            ->join('paket_kursus', 'peserta.paket_kursus_id', '=', 'paket_kursus.id')
            ->where('pembayaran.status', 'valid')->whereYear('pembayaran.tanggal', $year)
            ->select('paket_kursus.nama_paket', DB::raw('SUM(pembayaran.nominal) as total'))
            ->groupBy('paket_kursus.nama_paket')->get();

        $transactions = Pembayaran::with('peserta.paketKursus')
            ->where('status', 'valid')->whereYear('tanggal', $year)->latest('tanggal')->paginate(10);

        $availableYears = Pembayaran::selectRaw('DISTINCT YEAR(tanggal) as year')->orderBy('year', 'desc')->pluck('year');

        return view('laporan.pendapatan', compact('chartLabels', 'chartData', 'totalBulanan', 'totalTahunan', 'revenueByPackage', 'transactions', 'year', 'availableYears'));
    }

    public function exportPdfPendapatan(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $transactions = Pembayaran::with('peserta.paketKursus')->where('status', 'valid')->whereYear('tanggal', $year)->latest('tanggal')->get();
        $totalTahunan = $transactions->sum('nominal');
        $pdf = Pdf::loadView('laporan.pdf_pendapatan', compact('transactions', 'totalTahunan', 'year'));
        return $pdf->stream("laporan-pendapatan-{$year}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        return Excel::download(new PendapatanExport($year), "laporan-pendapatan-{$year}.xlsx");
    }
}

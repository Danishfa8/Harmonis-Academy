@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Total Peserta --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm fade-in-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Peserta</p>
                    <h3 class="mt-2 text-3xl font-bold text-slate-800">{{ $totalPeserta }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
        {{-- Peserta Aktif --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm fade-in-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Peserta Aktif</p>
                    <h3 class="mt-2 text-3xl font-bold text-slate-800">{{ $pesertaAktif }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                    <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m5 5.197V21"/></svg>
                </div>
            </div>
        </div>
        {{-- Peserta Selesai --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm fade-in-up" style="animation-delay:.2s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Peserta Selesai</p>
                    <h3 class="mt-2 text-3xl font-bold text-slate-800">{{ $pesertaSelesai }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
        </div>
        {{-- Paket Aktif --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm fade-in-up" style="animation-delay:.3s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Paket Kursus Aktif</p>
                    <h3 class="mt-2 text-3xl font-bold text-slate-800">{{ $paketAktif }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50">
                    <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CHART + PESERTA TERBARU ===== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 fade-in-up" style="animation-delay:.4s">
        {{-- LEFT: Pendapatan Bulanan Chart (span 2) --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Pendapatan Bulanan {{ $year }}</h3>
                    <p class="text-sm text-slate-400">Grafik pendapatan berdasarkan pembayaran valid</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="h-72">
                <canvas id="dashboardRevenueChart"></canvas>
            </div>
            <div class="mt-4 flex items-center justify-end">
                <div class="rounded-lg bg-red-50 border border-red-100 px-4 py-2">
                    <p class="text-xs text-slate-500">Total {{ $year }}</p>
                    <p class="text-lg font-bold text-red-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- RIGHT: Peserta Terbaru (span 1) --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-semibold text-slate-800">Peserta Terbaru</h3>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                    <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($pesertaTerbaru as $pt)
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                                {{ $pt->initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-800 truncate">{{ $pt->nama }}</p>
                                <p class="text-sm text-slate-400 truncate">{{ $pt->paketKursus->nama_paket ?? 'Tanpa Paket' }} &middot; {{ $pt->tgl_masuk->locale('id')->isoFormat('DD MMM Y') }}</p>
                            </div>
                        </div>
                        <span class="flex-shrink-0 inline-block rounded-lg px-2.5 py-1 text-xs font-semibold {{ $pt->status_bayar === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                            {{ $pt->status_bayar === 'lunas' ? 'Lunas' : 'Belum' }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-sm text-slate-400 py-8">Belum ada peserta.</p>
                @endforelse
            </div>
            @if(in_array(Auth::user()->role, ['superadmin', 'instruktur', 'admin']))
                <a href="{{ route('peserta.index') }}" class="mt-5 flex items-center justify-center gap-2 rounded-xl border border-slate-200 py-2.5 text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-red-600 transition-colors">
                    Lihat Semua Peserta
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dashboardRevenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(220, 38, 38, 0.15)');
    gradient.addColorStop(1, 'rgba(220, 38, 38, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($bulanLabels),
            datasets: [{
                label: 'Pendapatan',
                data: @json($bulanData),
                borderColor: '#DC2626',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#DC2626',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    borderColor: '#DC2626',
                    borderWidth: 1,
                    titleColor: '#fff',
                    titleFont: { weight: 'bold' },
                    bodyColor: '#94A3B8',
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(ctx) { return 'Rp ' + ctx.raw.toLocaleString('id-ID'); }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        callback: function(v) {
                            if (v >= 1000000) return 'Rp ' + (v / 1000000).toFixed(1) + 'M';
                            if (v >= 1000) return 'Rp ' + (v / 1000) + 'K';
                            return 'Rp ' + v;
                        }
                    },
                    beginAtZero: true,
                }
            }
        }
    });
});
</script>
@endsection
@endsection

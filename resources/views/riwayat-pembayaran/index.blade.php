@extends('layouts.app')
@section('title', 'Riwayat Pembayaran')
@section('page-title', 'Riwayat Pembayaran')

@section('content')
<div class="space-y-5 fade-in-up">
    {{-- Chart --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-slate-800">Total Pembayaran Bulanan</h3>
        <div class="h-72">
            <canvas id="monthlyPaymentChart"></canvas>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('riwayat-pembayaran.index') }}" class="flex items-center gap-3">
        <select name="peserta_id" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
            <option value="">Semua Peserta</option>
            @foreach($daftarPeserta as $p)
                <option value="{{ $p->id }}" {{ $pesertaId == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Filter</button>
    </form>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-5 py-4 font-semibold">Peserta</th><th class="px-5 py-4 font-semibold">Tanggal</th><th class="px-5 py-4 font-semibold">Metode</th><th class="px-5 py-4 font-semibold">Nominal</th><th class="px-5 py-4 font-semibold">Status</th><th class="px-5 py-4 font-semibold">Keterangan</th>
                </tr></thead>
                <tbody>
                    @forelse($pembayaran as $pay)
                    <tr class="border-b border-slate-200 table-row-hover">
                        <td class="px-5 py-4 font-semibold text-slate-800">{{ $pay->peserta->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $pay->tanggal->locale('id')->isoFormat('DD MMM Y') }}</td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-medium {{ $pay->metode==='transfer'?'bg-blue-50 text-blue-600 border border-blue-200':'bg-slate-100 text-slate-600 border border-slate-200' }}">{{ ucfirst($pay->metode) }}</span></td>
                        <td class="px-5 py-4 text-slate-800 font-semibold whitespace-nowrap">Rp {{ number_format($pay->nominal,0,',','.') }}</td>
                        <td class="px-5 py-4"><span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $pay->status==='valid'?'badge-valid':'badge-invalid' }}">{{ ucfirst($pay->status) }}</span></td>
                        <td class="px-5 py-4 text-slate-550 max-w-[200px] truncate">{{ $pay->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-550">Tidak ada riwayat pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayaran->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $pembayaran->withQueryString()->links() }}</div>@endif
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyPaymentChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(220, 38, 38, 0.2)');
    gradient.addColorStop(1, 'rgba(220, 38, 38, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($bulanLabels),
            datasets: [{
                label: 'Total Pembayaran',
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
                    backgroundColor: '#ffffff',
                    borderColor: '#DC2626',
                    borderWidth: 1,
                    titleColor: '#1e293b',
                    titleFont: { weight: 'bold' },
                    bodyColor: '#64748b',
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
                    ticks: { color: '#64748B', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                    ticks: {
                        color: '#64748B',
                        font: { size: 11 },
                        callback: function(v) {
                            if (v >= 1000000) return 'Rp ' + (v / 1000000).toFixed(1) + 'M';
                            if (v >= 1000) return 'Rp ' + (v / 1000) + 'K';
                            return 'Rp ' + v;
                        }
                    },
                    beginAtZero: true,
                    suggestedMax: Math.max(...@json($bulanData)) * 1.2 || 500000
                }
            }
        }
    });
});
</script>
@endsection
@endsection

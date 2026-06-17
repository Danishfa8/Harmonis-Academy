@extends('layouts.app')
@section('title', 'Laporan Pendapatan')
@section('page-title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-5 fade-in-up">
    <div class="flex items-center justify-between">
        <form method="GET" action="{{ route('laporan.pendapatan') }}" class="flex items-center gap-3">
            <select name="year" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                @foreach($availableYears as $y)<option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>@endforeach
                @if(!$availableYears->contains($year))<option value="{{ $year }}" selected>{{ $year }}</option>@endif
            </select>
            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Tampilkan</button>
        </form>
        <div class="flex items-center gap-2">
            <a href="{{ route('laporan.pendapatan.export-pdf', ['year'=>$year]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 transition-colors">PDF</a>
            <a href="{{ route('laporan.pendapatan.export-excel', ['year'=>$year]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-55 border border-emerald-200 px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-100 transition-colors">Excel</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div class="stat-card-3 rounded-2xl border border-slate-200 p-6"><p class="text-sm font-medium text-slate-500">Total Bulan Ini</p><h3 class="mt-2 text-2xl font-bold text-slate-800">Rp {{ number_format($totalBulanan, 0, ',', '.') }}</h3></div>
        <div class="stat-card-2 rounded-2xl border border-slate-200 p-6"><p class="text-sm font-medium text-slate-500">Total Tahun {{ $year }}</p><h3 class="mt-2 text-2xl font-bold text-slate-800">Rp {{ number_format($totalTahunan, 0, ',', '.') }}</h3></div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-slate-800">Grafik Pendapatan — {{ $year }}</h3>
        <div class="h-72"><canvas id="revenueChart"></canvas></div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/50 px-5 py-4"><h3 class="text-lg font-semibold text-slate-800">Detail Transaksi</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-5 py-4 font-semibold">Tanggal</th><th class="px-5 py-4 font-semibold">Peserta</th><th class="px-5 py-4 font-semibold">Paket</th><th class="px-5 py-4 font-semibold">Nominal</th><th class="px-5 py-4 font-semibold">Metode</th>
                </tr></thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-b border-slate-200 table-row-hover">
                        <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $t->tanggal->locale('id')->isoFormat('DD MMM Y') }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-800">{{ $t->peserta->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $t->peserta->paketKursus->nama_paket ?? '-' }}</td>
                        <td class="px-5 py-4 text-emerald-600 font-semibold whitespace-nowrap">Rp {{ number_format($t->nominal,0,',','.') }}</td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-medium {{ $t->metode==='transfer'?'bg-blue-50 text-blue-600 border border-blue-200':'bg-slate-100 text-slate-600 border border-slate-200' }}">{{ ucfirst($t->metode) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Tidak ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $transactions->withQueryString()->links() }}</div>@endif
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const g = ctx.createLinearGradient(0,0,0,280);
    g.addColorStop(0,'rgba(220, 38, 38, 0.2)');
    g.addColorStop(1,'rgba(220, 38, 38, 0)');
    new Chart(ctx,{type:'line',data:{labels:@json($chartLabels),datasets:[{label:'Pendapatan',data:@json($chartData),borderColor:'#DC2626',backgroundColor:g,borderWidth:3,fill:true,tension:0.4,pointBackgroundColor:'#DC2626',pointBorderColor:'#ffffff',pointBorderWidth:3,pointRadius:5,pointHoverRadius:8}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#ffffff',borderColor:'#DC2626',borderWidth:1,titleColor:'#1e293b',bodyColor:'#64748b',padding:12,displayColors:false,callbacks:{label:c=>'Rp '+c.raw.toLocaleString('id-ID')}}},scales:{x:{grid:{color:'rgba(226, 232, 240, 0.6)'},ticks:{color:'#64748B'}},y:{grid:{color:'rgba(226, 232, 240, 0.6)'},ticks:{color:'#64748B',callback:v=>v>=1e6?'Rp '+(v/1e6).toFixed(1)+'M':v>=1e3?'Rp '+(v/1e3)+'K':'Rp '+v},beginAtZero:true}}}});
});
</script>
@endsection
@endsection

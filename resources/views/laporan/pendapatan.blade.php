@extends('layouts.app')
@section('title', 'Laporan Pendapatan')
@section('page-title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-5 fade-in-up">
    <div class="flex items-center justify-between">
        <form method="GET" action="{{ route('laporan.pendapatan') }}" class="flex items-center gap-3">
            <select name="year" class="rounded-xl border border-slate-600/50 bg-[#253044] px-4 py-2.5 text-sm text-white outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                @foreach($availableYears as $y)<option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>@endforeach
                @if(!$availableYears->contains($year))<option value="{{ $year }}" selected>{{ $year }}</option>@endif
            </select>
            <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">Tampilkan</button>
        </form>
        <div class="flex items-center gap-2">
            <a href="{{ route('laporan.pendapatan.export-pdf', ['year'=>$year]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-red-500/15 border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/25">PDF</a>
            <a href="{{ route('laporan.pendapatan.export-excel', ['year'=>$year]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-2 text-sm font-medium text-emerald-400 hover:bg-emerald-500/25">Excel</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div class="stat-card-3 rounded-2xl border border-slate-700/50 p-6"><p class="text-sm font-medium text-slate-400">Total Bulan Ini</p><h3 class="mt-2 text-2xl font-bold text-white">Rp {{ number_format($totalBulanan, 0, ',', '.') }}</h3></div>
        <div class="stat-card-2 rounded-2xl border border-slate-700/50 p-6"><p class="text-sm font-medium text-slate-400">Total Tahun {{ $year }}</p><h3 class="mt-2 text-2xl font-bold text-white">Rp {{ number_format($totalTahunan, 0, ',', '.') }}</h3></div>
    </div>

    <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 shadow-xl">
        <h3 class="mb-4 text-lg font-semibold text-white">Grafik Pendapatan — {{ $year }}</h3>
        <div class="h-72"><canvas id="revenueChart"></canvas></div>
    </div>

    <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] shadow-xl overflow-hidden">
        <div class="border-b border-slate-700/50 px-5 py-4"><h3 class="text-lg font-semibold text-white">Detail Transaksi</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-700 text-xs uppercase tracking-wider text-slate-400">
                    <th class="px-5 py-4 font-semibold">Tanggal</th><th class="px-5 py-4 font-semibold">Peserta</th><th class="px-5 py-4 font-semibold">Paket</th><th class="px-5 py-4 font-semibold">Nominal</th><th class="px-5 py-4 font-semibold">Metode</th>
                </tr></thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-b border-slate-700/50 table-row-hover">
                        <td class="px-5 py-4 text-slate-300 whitespace-nowrap">{{ $t->tanggal->locale('id')->isoFormat('DD MMM Y') }}</td>
                        <td class="px-5 py-4 font-semibold text-white">{{ $t->peserta->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-300">{{ $t->peserta->paketKursus->nama_paket ?? '-' }}</td>
                        <td class="px-5 py-4 text-emerald-400 font-semibold whitespace-nowrap">Rp {{ number_format($t->nominal,0,',','.') }}</td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-medium {{ $t->metode==='transfer'?'bg-blue-500/15 text-blue-400 border border-blue-500/30':'bg-slate-600/30 text-slate-300 border border-slate-500/30' }}">{{ ucfirst($t->metode) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Tidak ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())<div class="border-t border-slate-700/50 px-5 py-4">{{ $transactions->withQueryString()->links() }}</div>@endif
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const g = ctx.createLinearGradient(0,0,0,280);
    g.addColorStop(0,'rgba(16,185,129,0.3)');
    g.addColorStop(1,'rgba(16,185,129,0)');
    new Chart(ctx,{type:'line',data:{labels:@json($chartLabels),datasets:[{label:'Pendapatan',data:@json($chartData),borderColor:'#10B981',backgroundColor:g,borderWidth:3,fill:true,tension:0.4,pointBackgroundColor:'#10B981',pointBorderColor:'#1C2434',pointBorderWidth:3,pointRadius:5,pointHoverRadius:8}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#1C2434',borderColor:'#10B981',borderWidth:1,titleColor:'#fff',bodyColor:'#94A3B8',padding:12,displayColors:false,callbacks:{label:c=>'Rp '+c.raw.toLocaleString('id-ID')}}},scales:{x:{grid:{color:'rgba(46,58,78,0.5)'},ticks:{color:'#64748B'}},y:{grid:{color:'rgba(46,58,78,0.5)'},ticks:{color:'#64748B',callback:v=>v>=1e6?'Rp '+(v/1e6).toFixed(1)+'M':v>=1e3?'Rp '+(v/1e3)+'K':'Rp '+v},beginAtZero:true}}}});
});
</script>
@endsection
@endsection

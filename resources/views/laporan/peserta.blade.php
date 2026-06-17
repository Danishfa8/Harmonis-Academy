@extends('layouts.app')
@section('title', 'Laporan Peserta')
@section('page-title', 'Laporan Peserta')

@section('content')
<div class="space-y-5 fade-in-up">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('laporan.peserta') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIK..." class="rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
            </div>
            <select name="status" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                <option value="">Semua Status</option>
                @foreach(['aktif','selesai'] as $s)
                    <option value="{{ $s }}" {{ $status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Filter</button>
        </form>
        <a href="{{ route('laporan.peserta.export-pdf', request()->only(['search', 'status'])) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-5 py-4 font-semibold">Nama</th><th class="px-5 py-4 font-semibold">Paket</th><th class="px-5 py-4 font-semibold">Tgl Daftar</th><th class="px-5 py-4 font-semibold">Status</th><th class="px-5 py-4 font-semibold">No HP</th><th class="px-5 py-4 font-semibold">Alamat</th>
                </tr></thead>
                <tbody>
                    @forelse($peserta as $p)
                    <tr class="border-b border-slate-200 table-row-hover">
                        <td class="px-5 py-4 font-semibold text-slate-800">{{ $p->nama }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $p->paketKursus->nama_paket ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $p->tgl_masuk->locale('id')->isoFormat('DD MMM Y') }}</td>
                        <td class="px-5 py-4">
                            @php $bc = match($p->status_pendaftaran){'aktif'=>'badge-aktif','selesai'=>'badge-selesai',default=>'badge-aktif'}; @endphp
                            <span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $bc }}">{{ ucfirst($p->status_pendaftaran) }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $p->no_hp }}</td>
                        <td class="px-5 py-4 text-slate-500 max-w-[200px] truncate">{{ $p->alamat }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">Tidak ada data peserta.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peserta->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $peserta->withQueryString()->links() }}</div>@endif
    </div>
</div>
@endsection

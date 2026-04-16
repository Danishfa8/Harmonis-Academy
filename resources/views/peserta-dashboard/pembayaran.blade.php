@extends('layouts.app')
@section('title', 'Pembayaran Saya')
@section('page-title', 'Pembayaran Saya')

@section('content')
<div class="space-y-6 fade-in-up">
    @if($peserta)
        {{-- TOTAL PEMBAYARAN VALID --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl">
            <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-2 font-semibold">TOTAL PEMBAYARAN VALID</h3>
            <div class="text-4xl sm:text-5xl font-black text-emerald-400 font-mono tracking-tight">
                Rp {{ number_format($totalValid, 0, ',', '.') }}
            </div>
        </div>

        {{-- TABEL RIWAYAT TRANSAKSI --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] shadow-xl overflow-hidden mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-700 text-xs uppercase tracking-wider text-slate-400 bg-[#192130]">
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Metode</th>
                            <th class="px-6 py-4 font-semibold">Nominal</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $p)
                            <tr class="border-b border-slate-700/50 table-row-hover">
                                <td class="px-6 py-4 text-slate-300 whitespace-nowrap">
                                    {{ $p->tanggal->locale('id')->isoFormat('DD MMM YYYY') }}
                                </td>
                                <td class="px-6 py-4 text-blue-400 capitalize">
                                    {{ $p->metode }}
                                </td>
                                <td class="px-6 py-4 text-emerald-400 font-semibold whitespace-nowrap">
                                    Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = match($p->status) {
                                            'valid' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                            'invalid' => 'bg-red-500/10 text-red-400 border border-red-500/20',
                                            default => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold {{ $badgeClass }} capitalize">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-300 max-w-xs truncate">
                                    {{ $p->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada riwayat pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 sm:p-12 shadow-xl text-center">
            <h3 class="text-xl font-bold text-white mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-400">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

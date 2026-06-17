@extends('layouts.app')
@section('title', 'Pembayaran Saya')
@section('page-title', 'Pembayaran Saya')

@section('content')
<div class="space-y-6 fade-in-up">
    @if($peserta)
        {{-- TOTAL PEMBAYARAN VALID --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-2 font-semibold">TOTAL PEMBAYARAN VALID</h3>
            <div class="text-4xl sm:text-5xl font-black text-emerald-600 font-mono tracking-tight">
                Rp {{ number_format($totalValid, 0, ',', '.') }}
            </div>
        </div>

        {{-- TABEL RIWAYAT TRANSAKSI --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 bg-slate-50/70">
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Metode</th>
                            <th class="px-6 py-4 font-semibold">Nominal</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $p)
                            <tr class="border-b border-slate-200 table-row-hover">
                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                    {{ $p->tanggal->locale('id')->isoFormat('DD MMM YYYY') }}
                                </td>
                                <td class="px-6 py-4 text-blue-600 capitalize font-medium">
                                    {{ $p->metode }}
                                </td>
                                <td class="px-6 py-4 text-emerald-600 font-bold whitespace-nowrap">
                                    Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = match($p->status) {
                                            'valid' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                            'invalid' => 'bg-red-50 text-red-600 border border-red-200',
                                            default => 'bg-amber-50 text-amber-600 border border-amber-200',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold {{ $badgeClass }} capitalize">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
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
        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 shadow-sm text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-550">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

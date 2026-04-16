@extends('layouts.app')
@section('title', 'Dashboard Saya')
@section('page-title', 'Dashboard Saya')

@section('content')
<div class="space-y-6 fade-in-up">
    @if($peserta)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- INFORMASI PESERTA --}}
            <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-6 font-semibold">INFORMASI PESERTA</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">Nama</span>
                        <span class="text-sm font-bold text-white">{{ $peserta->nama }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">NIK</span>
                        <span class="text-sm font-semibold text-white">{{ $peserta->nik }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">No HP</span>
                        <span class="text-sm font-semibold text-white">{{ $peserta->no_hp }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">Paket</span>
                        <span class="text-sm font-bold text-emerald-400">{{ $peserta->paketKursus->nama_paket ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">Tgl Masuk</span>
                        <span class="text-sm font-semibold text-white">{{ $peserta->tgl_masuk->locale('id')->isoFormat('DD MMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm text-slate-400">Status</span>
                        <span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $peserta->status_bayar === 'lunas' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                            {{ $peserta->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- STATUS PEMBAYARAN --}}
            <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl flex flex-col">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-6 font-semibold">STATUS PEMBAYARAN</h3>
                <div class="space-y-4 flex-1">
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">Total Harga</span>
                        <span class="text-sm font-bold text-white">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">Sudah Bayar</span>
                        <span class="text-sm font-bold text-emerald-400">Rp {{ number_format($sudahBayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-700/50 pb-3">
                        <span class="text-sm text-slate-400">Sisa</span>
                        <span class="text-sm font-bold text-amber-400">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-300">Progress Pembayaran</span>
                        <span class="text-sm font-bold text-white">{{ $progressB }}%</span>
                    </div>
                    <div class="h-3 w-full rounded-full bg-slate-700">
                        <div class="h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: {{ $progressB }}%; transition: width 1s ease-in-out;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- NILAI SKILL SAYA --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl mt-6">
            <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-6 font-semibold">NILAI SKILL SAYA</h3>
            
            @if($peserta->penilaianSkill)
                @php $ps = $peserta->penilaianSkill; @endphp
                <div class="flex flex-wrap justify-between items-center gap-6">
                    @foreach(['cutting' => 'Cutting', 'styling' => 'Styling', 'coloring' => 'Coloring', 'shaving' => 'Shaving', 'hygiene' => 'Hygiene'] as $k => $label)
                        @php
                            $val = $ps->$k;
                            $grade = $val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 60 ? 'C' : 'D'));
                            $colorClass = $val >= 85 ? 'text-emerald-400' : ($val >= 75 ? 'text-blue-400' : ($val >= 60 ? 'text-amber-400' : 'text-red-400'));
                        @endphp
                        <div class="flex flex-col items-center flex-1 min-w-[80px]">
                            <span class="text-4xl font-black mb-1 {{ $colorClass }}">{{ $grade }}</span>
                            <span class="text-sm font-medium text-slate-300">{{ $label }}</span>
                            <span class="text-sm font-bold text-white mt-1">{{ $val }}/100</span>
                        </div>
                    @endforeach
                </div>
                
                @if($ps->ulasan_instruktur)
                <div class="mt-8 pt-6 border-t border-slate-700/50">
                    <p class="text-sm text-slate-400 italic">Catatan: "{{ $ps->ulasan_instruktur }}"</p>
                </div>
                @endif
            @else
                <div class="py-8 text-center">
                    <p class="text-slate-500">Belum ada penilaian skill.</p>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 sm:p-12 shadow-xl text-center">
            <h3 class="text-xl font-bold text-white mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-400">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

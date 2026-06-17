@extends('layouts.app')
@section('title', 'Dashboard Saya')
@section('page-title', 'Dashboard Saya')

@section('content')
<div class="space-y-6 fade-in-up">
    @if($peserta)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- INFORMASI PESERTA --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-6 font-semibold">INFORMASI PESERTA</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-500">Nama</span>
                        <span class="text-sm font-bold text-slate-800">{{ $peserta->nama }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-500">NIK</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $peserta->nik }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-500">No HP</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $peserta->no_hp }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-500">Paket</span>
                        <span class="text-sm font-bold text-red-650">{{ $peserta->paketKursus->nama_paket ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-500">Tgl Masuk</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $peserta->tgl_masuk->locale('id')->isoFormat('DD MMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm text-slate-500">Status</span>
                        <span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $peserta->status_bayar === 'lunas' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-red-50 text-red-650 border border-red-200' }}">
                            {{ $peserta->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- STATUS PEMBAYARAN --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm flex flex-col">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-6 font-semibold">STATUS PEMBAYARAN</h3>
                <div class="space-y-4 flex-1">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-505">Total Harga</span>
                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-505">Sudah Bayar</span>
                        <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($sudahBayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-sm text-slate-505">Sisa</span>
                        <span class="text-sm font-bold text-amber-600">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-600">Progress Pembayaran</span>
                        <span class="text-sm font-bold text-slate-800">{{ $progressB }}%</span>
                    </div>
                    <div class="h-3 w-full rounded-full bg-slate-100">
                        <div class="h-3 rounded-full bg-red-650" style="width: {{ $progressB }}%; transition: width 1s ease-in-out;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- NILAI SKILL SAYA --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm mt-6">
            <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-6 font-semibold">NILAI SKILL SAYA</h3>
            
            @if($peserta->penilaianSkill)
                @php $ps = $peserta->penilaianSkill; @endphp
                <div class="flex flex-wrap justify-between items-center gap-6">
                    @foreach(['cutting' => 'Cutting', 'styling' => 'Styling', 'coloring' => 'Coloring', 'shaving' => 'Shaving', 'hygiene' => 'Hygiene'] as $k => $label)
                        @php
                            $val = $ps->$k;
                            $grade = $val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 60 ? 'C' : 'D'));
                            $colorClass = $val >= 85 ? 'text-emerald-600' : ($val >= 75 ? 'text-blue-600' : ($val >= 60 ? 'text-amber-600' : 'text-red-600'));
                        @endphp
                        <div class="flex flex-col items-center flex-1 min-w-[80px]">
                            <span class="text-4xl font-black mb-1 {{ $colorClass }}">{{ $grade }}</span>
                            <span class="text-sm font-medium text-slate-500">{{ $label }}</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $val }}/100</span>
                        </div>
                    @endforeach
                </div>
                
                @if($ps->ulasan_instruktur)
                <div class="mt-8 pt-6 border-t border-slate-200">
                    <p class="text-sm text-slate-500 italic">Catatan: "{{ $ps->ulasan_instruktur }}"</p>
                </div>
                @endif
            @else
                <div class="py-8 text-center">
                    <p class="text-slate-400">Belum ada penilaian skill.</p>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 shadow-sm text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-500">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

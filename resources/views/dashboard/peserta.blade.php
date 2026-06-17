@extends('layouts.app')
@section('title', 'Nilai & Progress')
@section('page-title', 'Nilai & Progress Saya')

@section('content')
<div class="space-y-6 fade-in-up max-w-4xl mx-auto">
    @if($peserta && $penilaian)
        {{-- Rata-rata Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm text-center">
            <p class="text-sm text-slate-550 uppercase tracking-wider mb-2 font-medium">Rata-rata Nilai</p>
            <div class="text-6xl sm:text-7xl font-black {{ $penilaian->rata_rata >= 80 ? 'text-emerald-600' : ($penilaian->rata_rata >= 70 ? 'text-blue-600' : 'text-red-600') }}">
                {{ $penilaian->rata_rata }}
            </div>
            <div class="mt-2">
                @php
                    $gradeBg = match($penilaian->grade) {
                        'A' => 'bg-emerald-50 text-emerald-600 border-emerald-250',
                        'B' => 'bg-blue-50 text-blue-600 border-blue-250',
                        'C' => 'bg-amber-50 text-amber-600 border-amber-250',
                        default => 'bg-red-50 text-red-650 border-red-250',
                    };
                @endphp
                <span class="inline-block rounded-xl px-5 py-1.5 text-lg font-bold border {{ $gradeBg }}">
                    Grade {{ $penilaian->grade }}
                </span>
            </div>
            <p class="mt-3 text-sm text-slate-500">
                <span class="text-slate-600 font-bold">{{ $peserta->nama }}</span> —
                {{ $peserta->paketKursus->nama_paket ?? 'Tanpa Paket' }}
            </p>
        </div>

        {{-- Skill Progress --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">Detail Penilaian Skill</h3>
            <div class="space-y-5">
                @foreach(['cutting','styling','coloring','shaving','hygiene'] as $skill)
                    @php
                        $val = $penilaian->$skill;
                        $barColor = $val >= 80 ? 'bg-emerald-500' : ($val >= 70 ? 'bg-blue-500' : 'bg-red-500');
                        $textColor = $val >= 80 ? 'text-emerald-650' : ($val >= 70 ? 'text-blue-600' : 'text-red-650');
                        $skillGrade = $val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 60 ? 'C' : ($val >= 50 ? 'D' : 'E')));
                        $skillGradeBg = $val >= 80 ? 'bg-emerald-50 text-emerald-650 border-emerald-200' : ($val >= 70 ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-red-50 text-red-650 border-red-200');
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-slate-800 capitalize">{{ $skill }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold {{ $textColor }}">{{ $val }}</span>
                                <span class="inline-block w-8 text-center rounded-md px-1.5 py-0.5 text-xs font-bold border {{ $skillGradeBg }}">{{ $skillGrade }}</span>
                            </div>
                        </div>
                        <div class="h-3 w-full rounded-full bg-slate-100">
                            <div class="h-3 rounded-full {{ $barColor }}" style="width: {{ $val }}%; transition: width 0.8s ease;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Catatan Instruktur --}}
        @if($penilaian->ulasan_instruktur)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Catatan Instruktur
                </span>
            </h3>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <p class="text-sm text-slate-600 leading-relaxed italic">"{{ $penilaian->ulasan_instruktur }}"</p>
            </div>
            <p class="mt-3 text-xs text-slate-500">Dinilai pada: {{ $penilaian->tanggal_penilaian->locale('id')->isoFormat('DD MMMM YYYY') }}</p>
        </div>
        @endif

    @elseif($peserta && !$penilaian)
        {{-- No Assessment Yet --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 shadow-sm text-center">
            <svg class="h-16 w-16 mx-auto text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Penilaian</h3>
            <p class="text-sm text-slate-550">Instruktur belum memberikan penilaian skill untuk Anda. Silakan hubungi instruktur untuk informasi lebih lanjut.</p>
        </div>
    @else
        {{-- No linked peserta --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 shadow-sm text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-550">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

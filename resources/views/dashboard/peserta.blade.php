@extends('layouts.app')
@section('title', 'Nilai & Progress')
@section('page-title', 'Nilai & Progress Saya')

@section('content')
<div class="space-y-6 fade-in-up max-w-4xl mx-auto">
    @if($peserta && $penilaian)
        {{-- Rata-rata Card --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl text-center">
            <p class="text-sm text-slate-400 uppercase tracking-wider mb-2">Rata-rata Nilai</p>
            <div class="text-6xl sm:text-7xl font-black {{ $penilaian->rata_rata >= 80 ? 'text-emerald-400' : ($penilaian->rata_rata >= 70 ? 'text-blue-400' : 'text-red-400') }}">
                {{ $penilaian->rata_rata }}
            </div>
            <div class="mt-2">
                @php
                    $gradeBg = match($penilaian->grade) {
                        'A' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                        'B' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                        'C' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                        default => 'bg-red-500/20 text-red-400 border-red-500/30',
                    };
                @endphp
                <span class="inline-block rounded-xl px-5 py-1.5 text-lg font-bold border {{ $gradeBg }}">
                    Grade {{ $penilaian->grade }}
                </span>
            </div>
            <p class="mt-3 text-sm text-slate-500">
                <span class="text-slate-400 font-medium">{{ $peserta->nama }}</span> —
                {{ $peserta->paketKursus->nama_paket ?? 'Tanpa Paket' }}
            </p>
        </div>

        {{-- Skill Progress --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl">
            <h3 class="text-lg font-semibold text-white mb-6">Detail Penilaian Skill</h3>
            <div class="space-y-5">
                @foreach(['cutting','styling','coloring','shaving','hygiene'] as $skill)
                    @php
                        $val = $penilaian->$skill;
                        $barColor = $val >= 80 ? 'bg-emerald-500' : ($val >= 70 ? 'bg-blue-500' : 'bg-red-500');
                        $textColor = $val >= 80 ? 'text-emerald-400' : ($val >= 70 ? 'text-blue-400' : 'text-red-400');
                        $skillGrade = $val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 60 ? 'C' : ($val >= 50 ? 'D' : 'E')));
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-slate-200 capitalize">{{ ucfirst($skill) }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold {{ $textColor }}">{{ $val }}</span>
                                <span class="inline-block w-8 text-center rounded-md px-1.5 py-0.5 text-xs font-bold border {{ $val >= 80 ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : ($val >= 70 ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30') }}">{{ $skillGrade }}</span>
                            </div>
                        </div>
                        <div class="h-3 w-full rounded-full bg-slate-700/60">
                            <div class="h-3 rounded-full {{ $barColor }} shadow-lg shadow-{{ $val >= 80 ? 'emerald' : ($val >= 70 ? 'blue' : 'red') }}-500/20" style="width: {{ $val }}%; transition: width 0.8s ease;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Catatan Instruktur --}}
        @if($penilaian->ulasan_instruktur)
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 sm:p-8 shadow-xl">
            <h3 class="text-lg font-semibold text-white mb-3">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Catatan Instruktur
                </span>
            </h3>
            <div class="rounded-xl bg-[#111827] border border-slate-600/30 p-4">
                <p class="text-sm text-slate-300 leading-relaxed italic">"{{ $penilaian->ulasan_instruktur }}"</p>
            </div>
            <p class="mt-3 text-xs text-slate-500">Dinilai pada: {{ $penilaian->tanggal_penilaian->locale('id')->isoFormat('DD MMMM YYYY') }}</p>
        </div>
        @endif

    @elseif($peserta && !$penilaian)
        {{-- No Assessment Yet --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 sm:p-12 shadow-xl text-center">
            <svg class="h-16 w-16 mx-auto text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Penilaian</h3>
            <p class="text-sm text-slate-400">Instruktur belum memberikan penilaian skill untuk Anda. Silakan hubungi instruktur untuk informasi lebih lanjut.</p>
        </div>
    @else
        {{-- No linked peserta --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 sm:p-12 shadow-xl text-center">
            <h3 class="text-xl font-bold text-white mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-400">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

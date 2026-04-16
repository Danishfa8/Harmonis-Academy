@extends('layouts.app')
@section('title', 'Nilai & Progress')
@section('page-title', 'Nilai & Progress Saya')

@section('content')
<div class="space-y-6 fade-in-up">
    @if($peserta && $peserta->penilaianSkill)
        @php $ps = $peserta->penilaianSkill; @endphp
        
        {{-- RATA-RATA NILAI --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 shadow-xl flex flex-col items-center justify-center text-center">
            <h2 class="text-[5rem] leading-none font-black mb-2 {{ $ps->rata_rata >= 80 ? 'text-emerald-400' : ($ps->rata_rata >= 60 ? 'text-blue-400' : 'text-amber-400') }}">
                {{ $ps->rata_rata }}
            </h2>
            <p class="text-xl font-medium text-slate-300">Rata-Rata Nilai</p>
            <p class="text-sm text-slate-500 mt-2 font-medium">{{ $ps->rata_rata >= 85 ? 'Luar Biasa!' : ($ps->rata_rata >= 75 ? 'Sangat Baik!' : 'Terus Tingkatkan!') }}</p>
        </div>

        {{-- GRID PROGRESS BAR SKILL --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach(['cutting' => 'Cutting', 'styling' => 'Styling', 'coloring' => 'Coloring', 'shaving' => 'Shaving', 'hygiene' => 'Hygiene'] as $k => $label)
                @php
                    $val = $ps->$k;
                    $grade = $val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 60 ? 'C' : 'D'));
                    $barColor = $val >= 85 ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 
                               ($val >= 75 ? 'bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]' : 
                               ($val >= 60 ? 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]' : 
                               'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]'));
                    $textGradeColor = str_contains($barColor, 'emerald') ? 'text-emerald-500' : (str_contains($barColor, 'blue') ? 'text-blue-500' : 'text-amber-500');
                @endphp
                <div class="rounded-xl border border-slate-700 bg-[rgba(25,33,48,0.6)] p-6 shadow-inner">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-white text-base">{{ $label }}</span>
                        <div class="flex items-center justify-center w-6 h-6 rounded-md bg-slate-800 border border-slate-600">
                            <span class="text-xs font-black {{ $textGradeColor }}">{{ $grade }}</span>
                        </div>
                    </div>
                    
                    <div class="h-3 w-full rounded-full bg-slate-800 p-0.5">
                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $val }}%; transition: width 1.5s ease-out;"></div>
                    </div>
                    <div class="text-right mt-2">
                        <span class="text-sm font-bold text-slate-300">{{ $val }}</span><span class="text-sm font-medium text-slate-500">/100</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CATATAN INSTRUKTUR --}}
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-6 shadow-xl mt-6">
            <h3 class="text-sm font-semibold text-white mb-2">Catatan Instruktur</h3>
            <p class="text-sm text-slate-400 italic leading-relaxed">
                "{{ $ps->ulasan_instruktur && $ps->ulasan_instruktur !== '' ? $ps->ulasan_instruktur : 'Belum ada catatan khusus.' }}"
            </p>
        </div>

    @elseif($peserta)
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 sm:p-12 shadow-xl text-center">
            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Penilaian</h3>
            <p class="text-sm text-slate-400">Instruktur belum memberikan penilaian skill untuk Anda. Silakan hubungi instruktur untuk informasi lebih lanjut.</p>
        </div>
    @else
        <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] p-8 sm:p-12 shadow-xl text-center">
            <h3 class="text-xl font-bold text-white mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-400">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

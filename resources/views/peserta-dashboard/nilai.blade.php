@extends('layouts.app')
@section('title', 'Nilai & Progress')
@section('page-title', 'Nilai & Progress Saya')

@section('content')
<div class="space-y-6 fade-in-up">
    @if($peserta && $peserta->penilaianSkill)
        @php $ps = $peserta->penilaianSkill; @endphp
        
        {{-- RATA-RATA NILAI --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm flex flex-col items-center justify-center text-center">
            <h2 class="text-[5rem] leading-none font-black mb-2 {{ $ps->rata_rata >= 80 ? 'text-emerald-600' : ($ps->rata_rata >= 60 ? 'text-blue-600' : 'text-amber-600') }}">
                {{ $ps->rata_rata }}
            </h2>
            <p class="text-xl font-medium text-slate-650">Rata-Rata Nilai</p>
            <p class="text-sm text-slate-500 mt-2 font-medium">{{ $ps->rata_rata >= 85 ? 'Luar Biasa!' : ($ps->rata_rata >= 75 ? 'Sangat Baik!' : 'Terus Tingkatkan!') }}</p>
        </div>

        {{-- GRID PROGRESS BAR SKILL --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach(['cutting' => 'Cutting', 'styling' => 'Styling', 'coloring' => 'Coloring', 'shaving' => 'Shaving', 'hygiene' => 'Hygiene'] as $k => $label)
                @php
                    $val = $ps->$k;
                    $grade = $val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 60 ? 'C' : 'D'));
                    $barColor = $val >= 85 ? 'bg-emerald-500' : 
                               ($val >= 75 ? 'bg-blue-500' : 
                               ($val >= 60 ? 'bg-amber-500' : 
                               'bg-red-500'));
                    $textGradeColor = $val >= 85 ? 'text-emerald-600' : ($val >= 75 ? 'text-blue-600' : ($val >= 60 ? 'text-amber-600' : 'text-red-600'));
                @endphp
                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-slate-800 text-base">{{ $label }}</span>
                        <div class="flex items-center justify-center w-6 h-6 rounded-md bg-white border border-slate-200">
                            <span class="text-xs font-black {{ $textGradeColor }}">{{ $grade }}</span>
                        </div>
                    </div>
                    
                    <div class="h-3 w-full rounded-full bg-slate-100 p-0.5">
                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $val }}%; transition: width 1.5s ease-out;"></div>
                    </div>
                    <div class="text-right mt-2">
                        <span class="text-sm font-bold text-slate-600">{{ $val }}</span><span class="text-sm font-medium text-slate-400">/100</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CATATAN INSTRUKTUR --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mt-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-2">Catatan Instruktur</h3>
            <p class="text-sm text-slate-500 italic leading-relaxed">
                "{{ $ps->ulasan_instruktur && $ps->ulasan_instruktur !== '' ? $ps->ulasan_instruktur : 'Belum ada catatan khusus.' }}"
            </p>
        </div>

    @elseif($peserta)
        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 shadow-sm text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Penilaian</h3>
            <p class="text-sm text-slate-550">Instruktur belum memberikan penilaian skill untuk Anda. Silakan hubungi instruktur untuk informasi lebih lanjut.</p>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 shadow-sm text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Profil Tidak Ditemukan</h3>
            <p class="text-sm text-slate-550">Akun Anda belum terhubung dengan data peserta. Silakan hubungi admin.</p>
        </div>
    @endif
</div>
@endsection

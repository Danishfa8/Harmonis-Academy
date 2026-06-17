@extends('layouts.app')
@section('title', 'Penilaian Skill / Progres')
@section('page-title', 'Penilaian Skill / Progres')

@section('content')
<div x-data="penilaianPage()" class="space-y-5 fade-in-up">
    {{-- Header: Search + Filter + Add Button --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <form method="GET" action="{{ route('penilaian-skill.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            <div class="relative flex-1 sm:max-w-sm">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama peserta..." class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
            </div>
            <select name="paket_kursus_id" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                <option value="">Semua Paket</option>
                @foreach($paketKursus as $pk)
                    <option value="{{ $pk->id }}" {{ ($paketFilter ?? '') == $pk->id ? 'selected' : '' }}>{{ $pk->nama_paket }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors whitespace-nowrap">Filter</button>
        </form>
        <button @click="openTambah()" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 whitespace-nowrap transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Penilaian
        </button>
    </div>

    {{-- Card Grid --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($penilaian as $i => $n)
            @php
                $colors = ['bg-emerald-500','bg-blue-500','bg-amber-500','bg-purple-500','bg-rose-500','bg-cyan-500','bg-teal-500','bg-indigo-500'];
                $bgColor = $colors[$i % count($colors)];
            @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $bgColor }} text-sm font-bold text-white">{{ $n->peserta->initials ?? '?' }}</div>
                        <div>
                            <h4 class="font-semibold text-slate-800">{{ $n->peserta->nama ?? '-' }}</h4>
                            <p class="text-xs text-slate-500">
                                Rata-rata: <span class="font-bold {{ $n->rata_rata >= 75 ? 'text-emerald-600' : ($n->rata_rata >= 60 ? 'text-blue-600' : ($n->rata_rata >= 50 ? 'text-amber-600' : 'text-red-600')) }}">{{ $n->rata_rata }}</span>
                                <span class="font-bold text-slate-400">({{ $n->grade }})</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click='openEdit(@json($n))' class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors" title="Edit">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button @click="hapus({{ $n->id }})" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50 hover:text-red-650 transition-colors" title="Hapus">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach(['cutting','styling','coloring','shaving','hygiene'] as $skill)
                        @php
                            $val = $n->$skill;
                            $barColor = $val >= 80 ? 'bg-emerald-500' : ($val >= 60 ? 'bg-blue-500' : ($val >= 40 ? 'bg-amber-500' : 'bg-red-500'));
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium text-slate-600 capitalize">{{ ucfirst($skill) }}</span>
                                <span class="text-xs font-bold text-slate-700">{{ $val }}</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-slate-100">
                                <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $val }}%; transition: width 0.6s ease;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($n->ulasan_instruktur)
                    <p class="mt-4 text-xs italic text-slate-500">"{{ $n->ulasan_instruktur }}"</p>
                @endif
            </div>
        @empty
            <div class="col-span-3 rounded-2xl border border-slate-200 bg-white px-5 py-12 text-center text-slate-400 shadow-sm">
                Belum ada data penilaian skill.
            </div>
        @endforelse
    </div>

    <form id="form-hapus-penilaian" method="POST" class="hidden">@csrf @method('DELETE')</form>
</div>

{{-- ===== MODAL TAMBAH ===== --}}
<div x-data x-show="$store.penilaianModal.show" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="$store.penilaianModal.close()" @keydown.escape.window="$store.penilaianModal.close()">

    <div class="w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-2xl [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.stop>

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800">Tambah Penilaian Skill</h3>
            <button @click="$store.penilaianModal.close()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('penilaian-skill.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Peserta</label>
                <select name="peserta_id" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                    <option value="">— Pilih Peserta —</option>
                    @foreach($pesertaTanpaNilai as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nik }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-650">Nilai Skill (0 - 100)</label>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                    @foreach(['cutting','styling','coloring','shaving','hygiene'] as $skill)
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500 capitalize">{{ $skill }}</label>
                        <input type="number" name="{{ $skill }}" value="0" min="0" max="100" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 text-center outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Tanggal Penilaian</label>
                <input type="date" name="tanggal_penilaian" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Ulasan Instruktur</label>
                <textarea name="ulasan_instruktur" rows="3" placeholder="Catatan penilaian..." class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors resize-none"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" @click="$store.penilaianModal.close()" class="rounded-lg border border-slate-300 bg-transparent px-6 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors">Batal</button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div x-data x-show="$store.editPenilaian.show" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="$store.editPenilaian.close()" @keydown.escape.window="$store.editPenilaian.close()">

    <div class="w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-2xl [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.stop>

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800">Edit Penilaian Skill</h3>
            <button @click="$store.editPenilaian.close()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form :action="$store.editPenilaian.formAction" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                <p class="text-sm text-slate-655 font-medium">Peserta: <span class="font-bold text-slate-800" x-text="$store.editPenilaian.pesertaNama"></span></p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Nilai Skill (0 - 100)</label>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                    @foreach(['cutting','styling','coloring','shaving','hygiene'] as $skill)
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500 capitalize">{{ $skill }}</label>
                        <input type="number" name="{{ $skill }}" x-model="$store.editPenilaian.form.{{ $skill }}" min="0" max="100" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 text-center outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Tanggal Penilaian</label>
                <input type="date" name="tanggal_penilaian" x-model="$store.editPenilaian.form.tanggal_penilaian" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Ulasan Instruktur</label>
                <textarea name="ulasan_instruktur" x-model="$store.editPenilaian.form.ulasan_instruktur" rows="3" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors resize-none"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" @click="$store.editPenilaian.close()" class="rounded-lg border border-slate-300 bg-transparent px-6 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors">Batal</button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('penilaianModal', {
        show: false,
        open() { this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; document.body.classList.remove('overflow-hidden'); },
    });
    Alpine.store('editPenilaian', {
        show: false, formAction: '', pesertaNama: '',
        form: { cutting:0, styling:0, coloring:0, shaving:0, hygiene:0, ulasan_instruktur:'', tanggal_penilaian:'' },
        open() { this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; document.body.classList.remove('overflow-hidden'); },
    });
});

function penilaianPage() {
    return {
        openTambah() { Alpine.store('penilaianModal').open(); },
        openEdit(n) {
            const s = Alpine.store('editPenilaian');
            s.formAction = '/penilaian-skill/' + n.id;
            s.pesertaNama = n.peserta?.nama || '-';
            s.form = {
                cutting: n.cutting, styling: n.styling, coloring: n.coloring,
                shaving: n.shaving, hygiene: n.hygiene,
                ulasan_instruktur: n.ulasan_instruktur || '',
                tanggal_penilaian: n.tanggal_penilaian?.split('T')[0] || ''
            };
            s.open();
        },
        hapus(id) {
            Swal.fire({ title:'Hapus penilaian?', text:'Data penilaian ini akan dihapus.', icon:'warning', showCancelButton:true, confirmButtonColor:'#EF4444', cancelButtonColor:'#64748B', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal', background:'#ffffff', color:'#1e293b' }).then(r => {
                if(r.isConfirmed){ const f=document.getElementById('form-hapus-penilaian'); f.action='/penilaian-skill/'+id; f.submit(); }
            });
        }
    }
}
</script>
@endsection
@endsection

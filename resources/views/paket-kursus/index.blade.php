@extends('layouts.app')
@section('title', 'Paket Kursus')
@section('page-title', 'Paket Kursus')

@section('content')
<div x-data="paketPage()" class="space-y-5 fade-in-up">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">Kelola paket kursus barbershop</p>
        <button @click="openTambah()" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Paket
        </button>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-4 font-semibold">Nama Paket</th>
                        <th class="px-5 py-4 font-semibold">Deskripsi</th>
                        <th class="px-5 py-4 font-semibold">Kategori</th>
                        <th class="px-5 py-4 font-semibold">Harga</th>
                        <th class="px-5 py-4 font-semibold">Lama Pelatihan</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Peserta</th>
                        <th class="px-5 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketKursus as $pkg)
                        <tr class="border-b border-slate-200 table-row-hover">
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $pkg->nama_paket }}</td>
                            <td class="px-5 py-4 text-slate-500 max-w-[200px] truncate">{{ $pkg->deskripsi ?? '-' }}</td>
                            <td class="px-5 py-4"><span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $pkg->kategori === 'berbayar' ? 'badge-lunas' : 'badge-selesai' }}">{{ ucfirst($pkg->kategori) }}</span></td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">Rp {{ number_format($pkg->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $pkg->lama_pelatihan }}</td>
                            <td class="px-5 py-4"><span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $pkg->status === 'aktif' ? 'badge-aktif' : 'badge-invalid' }}">{{ $pkg->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                            <td class="px-5 py-4 text-slate-600">{{ $pkg->peserta_count }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1">
                                    <button @click='openEdit(@json($pkg))' class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button @click="hapus({{ $pkg->id }}, '{{ $pkg->nama_paket }}')" class="rounded-lg p-1.5 text-slate-500 hover:bg-red-50 hover:text-red-600 transition-colors"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-12 text-center text-slate-500">Belum ada paket kursus.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paketKursus->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $paketKursus->links() }}</div>@endif
    </div>

    <form id="form-hapus-paket" method="POST" class="hidden">@csrf @method('DELETE')</form>
</div>

{{-- ===== MODAL TAMBAH/EDIT (INDEPENDENT FIXED OVERLAY) ===== --}}
<div x-data x-show="$store.paketModal.show" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="$store.paketModal.close()"
     @keydown.escape.window="$store.paketModal.close()">

    <div class="w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-200 bg-white p-8 shadow-2xl [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800" x-text="$store.paketModal.isEdit ? 'Edit Paket' : 'Tambah Paket'"></h3>
            <button @click="$store.paketModal.close()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form :action="$store.paketModal.formAction" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT" x-bind:disabled="!$store.paketModal.isEdit">

            {{-- Baris 1: Nama Paket (Full Width) --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Nama Paket</label>
                <input type="text" name="nama_paket" x-model="$store.paketModal.form.nama_paket" required
                       class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
            </div>

            {{-- Baris 2: Deskripsi (Full Width) --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Deskripsi</label>
                <textarea name="deskripsi" x-model="$store.paketModal.form.deskripsi" rows="3"
                          class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors resize-none"></textarea>
            </div>

            {{-- Baris 3: Kategori & Harga --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Kategori</label>
                    <select name="kategori" x-model="$store.paketModal.form.kategori" required
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                        <option value="berbayar">Berbayar</option>
                        <option value="gratis">Gratis</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Harga</label>
                    <input type="number" name="harga" x-model="$store.paketModal.form.harga" required min="0"
                           class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
            </div>

            {{-- Baris 4: Lama Pelatihan & Status --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Lama Pelatihan</label>
                    <input type="text" name="lama_pelatihan" x-model="$store.paketModal.form.lama_pelatihan" required placeholder="3 Bulan"
                           class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-600">Status</label>
                    <div class="flex items-center gap-6 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="aktif" x-model="$store.paketModal.form.status"
                                   class="h-4 w-4 border border-slate-300 bg-transparent text-red-600 focus:ring-red-500 focus:ring-offset-0 focus:ring-offset-transparent">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="tidak_aktif" x-model="$store.paketModal.form.status"
                                   class="h-4 w-4 border border-slate-300 bg-transparent text-red-600 focus:ring-red-500 focus:ring-offset-0 focus:ring-offset-transparent">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Tidak Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" @click="$store.paketModal.close()"
                        class="rounded-lg border border-slate-300 bg-transparent px-6 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="$store.paketModal.isEdit ? 'Perbarui' : 'Tambah'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('paketModal', {
        show: false, isEdit: false,
        formAction: '{{ route("paket-kursus.store") }}',
        form: { nama_paket:'', deskripsi:'', kategori:'berbayar', harga:0, lama_pelatihan:'', status:'aktif' },
        open() { this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; document.body.classList.remove('overflow-hidden'); },
    });
});

function paketPage() {
    return {
        openTambah() {
            const s = Alpine.store('paketModal');
            s.isEdit = false;
            s.formAction = '{{ route("paket-kursus.store") }}';
            s.form = { nama_paket:'', deskripsi:'', kategori:'berbayar', harga:0, lama_pelatihan:'', status:'aktif' };
            s.open();
        },
        openEdit(p) {
            const s = Alpine.store('paketModal');
            s.isEdit = true;
            s.formAction = '/paket-kursus/' + p.id;
            s.form = { nama_paket:p.nama_paket, deskripsi:p.deskripsi||'', kategori:p.kategori, harga:p.harga, lama_pelatihan:p.lama_pelatihan, status:p.status };
            s.open();
        },
        hapus(id, n) {
            Swal.fire({ title:'Hapus paket?', text:'Paket "'+n+'" akan dihapus.', icon:'warning', showCancelButton:true, confirmButtonColor:'#EF4444', cancelButtonColor:'#64748B', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal', background:'#ffffff', color:'#1e293b' }).then(r => {
                if(r.isConfirmed){ const f=document.getElementById('form-hapus-paket'); f.action='/paket-kursus/'+id; f.submit(); }
            });
        }
    }
}
</script>
@endsection
@endsection

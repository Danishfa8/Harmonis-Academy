@extends('layouts.app')
@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')
<div x-data="bayarPage()" class="space-y-5 fade-in-up">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('pembayaran.index') }}" class="relative w-full sm:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama peserta..." class="w-full rounded-xl border border-slate-600/50 bg-[#253044] py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
        </form>
        <button @click="openTambah()" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 hover:bg-emerald-600 whitespace-nowrap">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Pembayaran
        </button>
    </div>

    <div class="rounded-2xl border border-slate-700/50 bg-[#1C2434] shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-700 text-xs uppercase tracking-wider text-slate-400">
                    <th class="px-5 py-4 font-semibold">Peserta</th><th class="px-5 py-4 font-semibold">Tanggal</th><th class="px-5 py-4 font-semibold">Metode</th><th class="px-5 py-4 font-semibold">Nominal</th><th class="px-5 py-4 font-semibold">Status</th><th class="px-5 py-4 font-semibold">Keterangan</th><th class="px-5 py-4 font-semibold">Aksi</th>
                </tr></thead>
                <tbody>
                    @forelse($pembayaran as $pay)
                    <tr class="border-b border-slate-700/50 table-row-hover">
                        <td class="px-5 py-4 font-semibold text-white">{{ $pay->peserta->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-300 whitespace-nowrap">{{ $pay->tanggal->locale('id')->isoFormat('DD MMM Y') }}</td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-medium {{ $pay->metode==='transfer'?'bg-blue-500/15 text-blue-400 border border-blue-500/30':'bg-slate-600/30 text-slate-300 border border-slate-500/30' }}">{{ ucfirst($pay->metode) }}</span></td>
                        <td class="px-5 py-4 text-white font-semibold whitespace-nowrap">Rp {{ number_format($pay->nominal,0,',','.') }}</td>
                        <td class="px-5 py-4"><span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $pay->status==='valid'?'badge-valid':'badge-invalid' }}">{{ ucfirst($pay->status) }}</span></td>
                        <td class="px-5 py-4 text-slate-400 max-w-[150px] truncate">{{ $pay->keterangan ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1">
                                <button @click='openEdit(@json($pay))' class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-700 hover:text-white"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <button @click="hapus({{ $pay->id }})" class="rounded-lg p-1.5 text-slate-400 hover:bg-red-500/20 hover:text-red-400"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-slate-500">Belum ada data pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayaran->hasPages())<div class="border-t border-slate-700/50 px-5 py-4">{{ $pembayaran->withQueryString()->links() }}</div>@endif
    </div>

    <form id="form-hapus-bayar" method="POST" class="hidden">@csrf @method('DELETE')</form>
</div>

{{-- ===== MODAL TAMBAH/EDIT (INDEPENDENT FIXED OVERLAY) ===== --}}
<div x-data x-show="$store.bayarModal.show" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="$store.bayarModal.close()"
     @keydown.escape.window="$store.bayarModal.close()">

    <div class="w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-700 bg-[#1C2434] p-8 shadow-2xl [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white" x-text="$store.bayarModal.isEdit ? 'Edit Pembayaran' : 'Tambah Pembayaran'"></h3>
            <button @click="$store.bayarModal.close()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-700 hover:text-white transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form :action="$store.bayarModal.formAction" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <template x-if="$store.bayarModal.isEdit"><input type="hidden" name="_method" value="PUT"></template>

            {{-- Baris 1: Peserta (Full Width) --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-400">Peserta</label>
                <select name="peserta_id" x-model="$store.bayarModal.form.peserta_id" required
                        class="w-full rounded-xl border border-slate-600/50 bg-[#111827] px-4 py-3 text-sm text-white outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                    <option value="">— Pilih Peserta —</option>
                    @foreach($daftarPeserta as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Baris 2: Tanggal & Metode --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-400">Tanggal</label>
                    <input type="date" name="tanggal" x-model="$store.bayarModal.form.tanggal" required
                           class="w-full rounded-xl border border-slate-600/50 bg-[#111827] px-4 py-3 text-sm text-white outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-400">Metode</label>
                    <select name="metode" x-model="$store.bayarModal.form.metode" required
                            class="w-full rounded-xl border border-slate-600/50 bg-[#111827] px-4 py-3 text-sm text-white outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>

            {{-- Baris 3: Nominal & Status --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-400">Nominal</label>
                    <input type="number" name="nominal" x-model="$store.bayarModal.form.nominal" required min="0"
                           class="w-full rounded-xl border border-slate-600/50 bg-[#111827] px-4 py-3 text-sm text-white outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-400">Status</label>
                    <div class="flex items-center gap-6 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="valid" x-model="$store.bayarModal.form.status"
                                   class="h-4 w-4 border-2 border-slate-500 bg-transparent text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0 focus:ring-offset-transparent">
                            <span class="text-sm text-slate-300 group-hover:text-white transition-colors">Valid</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="invalid" x-model="$store.bayarModal.form.status"
                                   class="h-4 w-4 border-2 border-slate-500 bg-transparent text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0 focus:ring-offset-transparent">
                            <span class="text-sm text-slate-300 group-hover:text-white transition-colors">Invalid</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Baris 4: Keterangan (Full Width) --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-400">Keterangan</label>
                <textarea name="keterangan" x-model="$store.bayarModal.form.keterangan" rows="3"
                          class="w-full rounded-xl border border-slate-600/50 bg-[#111827] px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors resize-none"></textarea>
            </div>

            {{-- Upload Bukti Pembayaran (Dashed Box) --}}
            <div x-data="{ photoPreview: null }">
                <label class="group relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-600 bg-[#111827]/50 p-6 overflow-hidden cursor-pointer hover:border-emerald-500 hover:bg-slate-800/50 transition-all duration-200">
                    <img x-show="photoPreview" :src="photoPreview" class="mt-2 rounded-lg max-h-48 object-cover z-10">
                    
                    <div x-show="!photoPreview" class="flex flex-col items-center">
                        <svg class="h-8 w-8 text-slate-500 group-hover:text-emerald-400 transition-colors mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <span class="text-sm font-medium text-slate-400 group-hover:text-slate-300 transition-colors">Upload Bukti Pembayaran</span>
                        <span class="text-xs text-slate-500">JPG, PNG, PDF maks. 2MB</span>
                    </div>
                    <input type="file" name="bukti_pembayaran" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" @change="if($event.target.files[0]) photoPreview = URL.createObjectURL($event.target.files[0])">
                </label>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-700/50">
                <button type="button" @click="$store.bayarModal.close()"
                        class="rounded-lg border border-slate-600 bg-transparent px-6 py-2.5 text-sm font-medium text-slate-400 hover:bg-slate-700 hover:text-white transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 hover:bg-emerald-600 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="$store.bayarModal.isEdit ? 'Perbarui' : 'Tambah'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('bayarModal', {
        show: false, isEdit: false,
        formAction: '{{ route("pembayaran.store") }}',
        form: { peserta_id:'', tanggal:'{{ date("Y-m-d") }}', metode:'cash', nominal:'', status:'valid', keterangan:'' },
        open() { this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; document.body.classList.remove('overflow-hidden'); },
    });
});

function bayarPage() {
    return {
        openTambah() {
            const s = Alpine.store('bayarModal');
            s.isEdit = false;
            s.formAction = '{{ route("pembayaran.store") }}';
            s.form = { peserta_id:'', tanggal:'{{ date("Y-m-d") }}', metode:'cash', nominal:'', status:'valid', keterangan:'' };
            s.open();
        },
        openEdit(p) {
            const s = Alpine.store('bayarModal');
            s.isEdit = true;
            s.formAction = '/pembayaran/' + p.id;
            s.form = { peserta_id:p.peserta_id, tanggal:p.tanggal?.split('T')[0]||'', metode:p.metode, nominal:p.nominal, status:p.status, keterangan:p.keterangan||'' };
            s.open();
        },
        hapus(id) {
            Swal.fire({ title:'Hapus pembayaran?', text:'Data pembayaran ini akan dihapus.', icon:'warning', showCancelButton:true, confirmButtonColor:'#EF4444', cancelButtonColor:'#475569', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal', background:'#1C2434', color:'#e2e8f0' }).then(r => {
                if(r.isConfirmed){ const f=document.getElementById('form-hapus-bayar'); f.action='/pembayaran/'+id; f.submit(); }
            });
        }
    }
}
</script>
@endsection
@endsection

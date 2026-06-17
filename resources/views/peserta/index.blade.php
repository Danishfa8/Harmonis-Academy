@extends('layouts.app')
@section('title', 'Peserta Kursus')
@section('page-title', 'Peserta Kursus')

@section('content')
<div x-data="pesertaPage()" class="space-y-5 fade-in-up">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('peserta.index') }}" class="relative w-full sm:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIK..." class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
        </form>
        <button @click="openTambah()" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 whitespace-nowrap transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Peserta
        </button>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-4 font-semibold">Nama</th>
                        <th class="px-5 py-4 font-semibold">No. HP</th>
                        <th class="px-5 py-4 font-semibold">Alamat</th>
                        <th class="px-5 py-4 font-semibold">Tgl Masuk</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Biaya Pengajar</th>
                        <th class="px-5 py-4 font-semibold">Paket</th>
                        <th class="px-5 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $i => $p)
                        @php $colors = ['avatar-a','avatar-b','avatar-c','avatar-d','avatar-e','avatar-f','avatar-g','avatar-h']; @endphp
                        <tr class="border-b border-slate-200 table-row-hover">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($p->foto)
                                        <img src="{{ asset('storage/' . $p->foto) }}" class="h-9 w-9 rounded-full object-cover flex-shrink-0" alt="{{ $p->nama }}">
                                    @else
                                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full {{ $colors[$i % count($colors)] }} text-sm font-bold text-white">{{ $p->initials }}</div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $p->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $p->nik }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $p->no_hp }}</td>
                            <td class="px-5 py-4 text-slate-600 max-w-[180px] truncate">{{ $p->alamat }}</td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $p->tgl_masuk->locale('id')->isoFormat('DD MMM Y') }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-block rounded-lg px-3 py-1 text-xs font-semibold {{ $p->status_bayar === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">{{ $p->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">Rp {{ number_format($p->biaya_pengajar, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $p->paketKursus->nama_paket ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1">
                                    <button @click='openShow(@json($p), @json($p->paketKursus))' class="rounded-lg p-1.5 text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Detail">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click='openEdit(@json($p))' class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="hapus({{ $p->id }}, '{{ $p->nama }}')" class="rounded-lg p-1.5 text-slate-500 hover:bg-red-50 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-12 text-center text-slate-500">Belum ada data peserta.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peserta->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $peserta->withQueryString()->links() }}</div>
        @endif
    </div>

    <form id="form-hapus" method="POST" class="hidden">@csrf @method('DELETE')</form>
</div>

{{-- ===== MODAL SHOW DETAIL ===== --}}
<div x-data="{ ...showModal() }" x-show="$store.showPeserta.show" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="$store.showPeserta.close()" @keydown.escape.window="$store.showPeserta.close()">

    <div class="w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-2xl [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.stop>

        <div class="flex items-center justify-between mb-6 border-b border-slate-200 pb-4">
            <h3 class="text-xl font-bold text-slate-800">Detail Peserta</h3>
            <div class="flex items-center gap-3">
                <a :href="'/peserta/' + $store.showPeserta.data?.id + '/pdf'" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Cetak PDF
                </a>
                <button @click="$store.showPeserta.close()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Info Pribadi & Kursus --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">Nama Lengkap</p><p class="text-sm text-slate-800 mt-1 font-semibold" x-text="$store.showPeserta.data?.nama"></p></div>
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">NIK</p><p class="text-sm text-slate-800 mt-1" x-text="$store.showPeserta.data?.nik"></p></div>
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">No HP / WA</p><p class="text-sm text-slate-800 mt-1" x-text="$store.showPeserta.data?.no_hp"></p></div>
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">Email</p><p class="text-sm text-slate-800 mt-1" x-text="$store.showPeserta.data?.email || '-'"></p></div>
                    <div class="sm:col-span-2"><p class="text-xs text-slate-500 uppercase tracking-wider">Alamat</p><p class="text-sm text-slate-800 mt-1" x-text="$store.showPeserta.data?.alamat"></p></div>
                </div>

                <div class="border-t border-slate-200 pt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">Paket Kursus</p><p class="text-sm text-red-600 mt-1 font-semibold" x-text="$store.showPeserta.paket?.nama_paket || '-'"></p></div>
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">Tanggal Masuk</p><p class="text-sm text-slate-800 mt-1" x-text="formatDate($store.showPeserta.data?.tgl_masuk)"></p></div>
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">Status Pembayaran</p><p class="text-sm mt-1 font-semibold" :class="$store.showPeserta.data?.status_bayar === 'lunas' ? 'text-emerald-600' : 'text-red-600'" x-text="$store.showPeserta.data?.status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas'"></p></div>
                    <div><p class="text-xs text-slate-500 uppercase tracking-wider">Status Pendaftaran</p><p class="text-sm text-slate-800 mt-1 capitalize" x-text="$store.showPeserta.data?.status_pendaftaran"></p></div>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Deskripsi / Catatan Tambahan</p>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed" x-text="$store.showPeserta.data?.deskripsi || '-'"></p>
                </div>
            </div>

            {{-- Dokumen Media --}}
            <div class="space-y-6">
                <div>
                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Foto Profil</h4>
                    <template x-if="$store.showPeserta.data?.foto">
                        <img :src="'/storage/' + $store.showPeserta.data?.foto" class="w-full rounded-xl border border-slate-200 object-cover aspect-square">
                    </template>
                    <template x-if="!$store.showPeserta.data?.foto">
                        <div class="w-full aspect-square rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center text-slate-400">
                            <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-xs font-medium">Tidak ada foto</span>
                        </div>
                    </template>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Dokumen KTP/SIM</h4>
                    <template x-if="$store.showPeserta.data?.ktp_sim">
                        <div>
                            <template x-if="isImage($store.showPeserta.data?.ktp_sim)">
                                <img :src="'/storage/' + $store.showPeserta.data?.ktp_sim" class="w-full rounded-xl border border-slate-200 object-cover">
                            </template>
                            <template x-if="!isImage($store.showPeserta.data?.ktp_sim)">
                                <a :href="'/storage/' + $store.showPeserta.data?.ktp_sim" target="_blank" class="flex items-center gap-2 p-4 rounded-xl bg-slate-50 border border-slate-200 text-red-600 hover:bg-slate-100 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-sm font-medium">Lihat Dokumen PDF</span>
                                </a>
                            </template>
                        </div>
                    </template>
                    <template x-if="!$store.showPeserta.data?.ktp_sim">
                        <div class="w-full p-6 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center text-slate-400">
                            <span class="text-xs font-medium">Tidak ada dokumen</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL TAMBAH/EDIT ===== --}}
<div x-data="{ photoPreview: null, ktpPreview: null }" x-show="$store.pesertaModal.show" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="$store.pesertaModal.close()" @keydown.escape.window="$store.pesertaModal.close()">

    <div class="w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-2xl [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.stop>

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800" x-text="$store.pesertaModal.isEdit ? 'Edit Peserta' : 'Tambah Peserta'"></h3>
            <button @click="$store.pesertaModal.close()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form :action="$store.pesertaModal.formAction" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT" x-bind:disabled="!$store.pesertaModal.isEdit">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Nama Lengkap</label>
                    <input type="text" name="nama" x-model="$store.pesertaModal.form.nama" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">NIK (Password Default)</label>
                    <input type="text" name="nik" x-model="$store.pesertaModal.form.nik" required maxlength="20" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" x-model="$store.pesertaModal.form.no_hp" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" x-model="$store.pesertaModal.form.tgl_masuk" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Alamat Lengkap</label>
                <input type="text" name="alamat" x-model="$store.pesertaModal.form.alamat" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Paket Kursus</label>
                    <select name="paket_kursus_id" x-model="$store.pesertaModal.form.paket_kursus_id" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                        <option value="">— Pilih Paket —</option>
                        @foreach($paketKursus as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->nama_paket }} (Rp {{ number_format($pkg->harga, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Biaya Pengajar</label>
                    <input type="number" name="biaya_pengajar" x-model="$store.pesertaModal.form.biaya_pengajar" required min="0" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-600">Email Utama</label>
                    <input type="email" name="email" x-model="$store.pesertaModal.form.email" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-600">Status Pembayaran</label>
                    <div class="flex items-center gap-6 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status_bayar" value="lunas" x-model="$store.pesertaModal.form.status_bayar" class="h-4 w-4 border border-slate-300 bg-transparent text-red-600 focus:ring-red-500 focus:ring-offset-0">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Lunas</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status_bayar" value="belum_lunas" x-model="$store.pesertaModal.form.status_bayar" class="h-4 w-4 border border-slate-300 bg-transparent text-red-600 focus:ring-red-500 focus:ring-offset-0">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Belum</span>
                        </label>
                    </div>
                </div>
            </div>

            <input type="hidden" name="status_pendaftaran" value="aktif">

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-600">Deskripsi / Catatan Tambahan</label>
                <textarea name="deskripsi" x-model="$store.pesertaModal.form.deskripsi" rows="3" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                {{-- Foto Upload with Preview --}}
                <label class="group relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 overflow-hidden cursor-pointer hover:border-red-500 hover:bg-slate-100 transition-all duration-200">
                    <template x-if="photoPreview || $store.pesertaModal.form.foto_url">
                        <div class="absolute inset-0 w-full h-full">
                            <img :src="photoPreview || $store.pesertaModal.form.foto_url" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <span class="text-xs font-semibold text-white bg-slate-800/80 px-3 py-1.5 rounded-lg shadow-lg">Ganti Foto</span>
                            </div>
                        </div>
                    </template>
                    <template x-if="!photoPreview && !$store.pesertaModal.form.foto_url">
                        <div class="flex flex-col items-center">
                            <svg class="h-8 w-8 text-slate-400 group-hover:text-red-500 transition-colors mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.41a2.25 2.25 0 013.182 0l2.909 2.91m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span class="text-sm font-medium text-slate-500 group-hover:text-slate-700">Upload Foto Profil</span>
                            <span class="text-xs text-slate-400">JPG, PNG maks. 2MB</span>
                        </div>
                    </template>
                    <input type="file" name="foto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="if($event.target.files[0]) photoPreview = URL.createObjectURL($event.target.files[0])">
                </label>

                {{-- KTP/SIM Upload with Preview --}}
                <label class="group relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 overflow-hidden cursor-pointer hover:border-red-500 hover:bg-slate-100 transition-all duration-200">
                    <template x-if="ktpPreview || $store.pesertaModal.form.ktp_url">
                        <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-slate-900/80 z-10 p-4 text-center">
                            <template x-if="(ktpPreview && !ktpPreview.includes('pdf')) || ($store.pesertaModal.form.ktp_url && !$store.pesertaModal.form.ktp_url.endsWith('.pdf'))">
                                <img :src="ktpPreview || $store.pesertaModal.form.ktp_url" class="absolute inset-0 w-full h-full object-cover opacity-60">
                            </template>
                            <svg class="h-8 w-8 text-red-500 mb-2 relative z-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-xs font-semibold text-red-500 relative z-20" x-text="ktpPreview ? 'File Siap Diupload' : 'Dokumen Tersimpan'"></span>
                            <span class="text-[10px] text-slate-300 mt-1 relative z-20 group-hover:text-white transition-colors">Klik untuk mengganti</span>
                        </div>
                    </template>
                    <template x-if="!ktpPreview && !$store.pesertaModal.form.ktp_url">
                        <div class="flex flex-col items-center">
                            <svg class="h-8 w-8 text-slate-400 group-hover:text-red-500 transition-colors mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
                            <span class="text-sm font-medium text-slate-500 group-hover:text-slate-700">Upload KTP/SIM</span>
                            <span class="text-xs text-slate-400">JPG, PNG, PDF maks. 2MB</span>
                        </div>
                    </template>
                    <input type="file" name="ktp_sim" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" @change="if($event.target.files[0]) ktpPreview = URL.createObjectURL($event.target.files[0])">
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" @click="$store.pesertaModal.close()" class="rounded-lg border border-slate-300 bg-transparent px-6 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors">Batal</button>
                <button type="submit" x-bind:disabled="!$store.pesertaModal.form.nama || !$store.pesertaModal.form.nik || !$store.pesertaModal.form.no_hp || !$store.pesertaModal.form.tgl_masuk || !$store.pesertaModal.form.alamat" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="$store.pesertaModal.isEdit ? 'Perbarui' : 'Tambah'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Modal Show
    Alpine.store('showPeserta', {
        show: false, data: null, paket: null,
        open(data, paket) { this.data = data; this.paket = paket; this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; setTimeout(() => this.data = null, 200); document.body.classList.remove('overflow-hidden'); }
    });

    // Modal Create/Edit
    Alpine.store('pesertaModal', {
        show: false, isEdit: false,
        formAction: '{{ route("peserta.store") }}',
        form: { nama:'', nik:'', no_hp:'', alamat:'', tgl_masuk:'', status_bayar:'belum_lunas', biaya_pengajar:0, paket_kursus_id:'', deskripsi:'', email:'', foto_url:null, ktp_url:null },
        open() { this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; document.body.classList.remove('overflow-hidden'); },
    });
});

function pesertaPage() {
    return {
        openTambah() {
            const s = Alpine.store('pesertaModal');
            s.isEdit = false;
            s.formAction = '{{ route("peserta.store") }}';
            s.form = { nama:'',nik:'',no_hp:'',alamat:'',tgl_masuk:'',status_bayar:'belum_lunas',biaya_pengajar:0,paket_kursus_id:'',deskripsi:'',email:'', foto_url:null, ktp_url:null };
            // Reset previews in the component scope
            const el = document.querySelector('[x-data="{ photoPreview: null, ktpPreview: null }"]');
            if(el && el.__x) { el.__x.$data.photoPreview = null; el.__x.$data.ktpPreview = null; }
            s.open();
        },
        openEdit(p) {
            const s = Alpine.store('pesertaModal');
            s.isEdit = true;
            s.formAction = '/peserta/' + p.id;
            s.form = { 
                nama:p.nama, nik:p.nik, no_hp:p.no_hp, alamat:p.alamat, 
                tgl_masuk:p.tgl_masuk?.split('T')[0]||'', status_bayar:p.status_bayar, 
                biaya_pengajar:p.biaya_pengajar, paket_kursus_id:p.paket_kursus_id||'', 
                deskripsi:p.deskripsi||'', email:p.email||'',
                foto_url: p.foto ? '/storage/' + p.foto : null,
                ktp_url: p.ktp_sim ? '/storage/' + p.ktp_sim : null
            };
            const el = document.querySelector('[x-data="{ photoPreview: null, ktpPreview: null }"]');
            if(el && el.__x) { el.__x.$data.photoPreview = null; el.__x.$data.ktpPreview = null; }
            s.open();
        },
        openShow(p, paket) {
            Alpine.store('showPeserta').open(p, paket);
        },
        hapus(id, nama) {
            Swal.fire({ title:'Hapus peserta?', text:'Data "'+nama+'" akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonColor:'#EF4444', cancelButtonColor:'#64748B', confirmButtonText:'Ya, Hapus!', cancelButtonText:'Batal', background:'#ffffff', color:'#1e293b' }).then(r => {
                if(r.isConfirmed){ const f=document.getElementById('form-hapus'); f.action='/peserta/'+id; f.submit(); }
            });
        }
    }
}

function showModal() {
    return {
        formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        },
        isImage(fileName) {
            if (!fileName) return false;
            return fileName.match(/\.(jpeg|jpg|gif|png)$/i) != null;
        }
    }
}
</script>
@endsection
@endsection

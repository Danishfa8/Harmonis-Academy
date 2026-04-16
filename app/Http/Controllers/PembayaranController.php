<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $pembayaran = Pembayaran::with('peserta.paketKursus')
            ->when($search, fn($q) => $q->whereHas('peserta', fn($p) => $p->where('nama', 'like', "%{$search}%")))
            ->latest()->paginate(10);
        $daftarPeserta = Peserta::orderBy('nama')->get();

        return view('pembayaran.index', compact('pembayaran', 'search', 'daftarPeserta'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'tanggal' => 'required|date',
            'metode' => 'required|in:transfer,cash',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:valid,invalid',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        if ($request->hasFile('bukti_pembayaran'))
            $v['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pembayaran/bukti', 'public');

        Pembayaran::create($v);
        return back()->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $v = $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'tanggal' => 'required|date',
            'metode' => 'required|in:transfer,cash',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:valid,invalid',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        if ($request->hasFile('bukti_pembayaran')) {
            if ($pembayaran->bukti_pembayaran) Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            $v['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pembayaran/bukti', 'public');
        }
        $pembayaran->update($v);
        return back()->with('success', 'Pembayaran berhasil diperbarui!');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_pembayaran) Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        $pembayaran->delete();
        return back()->with('success', 'Pembayaran berhasil dihapus!');
    }

    public function downloadBukti(Pembayaran $pembayaran)
    {
        if (!$pembayaran->bukti_pembayaran || !Storage::disk('public')->exists($pembayaran->bukti_pembayaran))
            return back()->with('error', 'Bukti pembayaran tidak tersedia.');
        return Storage::disk('public')->download($pembayaran->bukti_pembayaran);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PaketKursus;
use Illuminate\Http\Request;

class PaketKursusController extends Controller
{
    public function index()
    {
        $paketKursus = PaketKursus::withCount('peserta')->latest()->paginate(10);
        return view('paket-kursus.index', compact('paketKursus'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:berbayar,gratis',
            'harga' => 'required|numeric|min:0',
            'lama_pelatihan' => 'required|string|max:100',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);
        PaketKursus::create($v);
        return back()->with('success', 'Paket kursus berhasil ditambahkan!');
    }

    public function update(Request $request, PaketKursus $paketKursus)
    {
        $v = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:berbayar,gratis',
            'harga' => 'required|numeric|min:0',
            'lama_pelatihan' => 'required|string|max:100',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);
        $paketKursus->update($v);
        return back()->with('success', 'Paket kursus berhasil diperbarui!');
    }

    public function destroy(PaketKursus $paketKursus)
    {
        $paketKursus->delete();
        return back()->with('success', 'Paket kursus berhasil dihapus!');
    }
}

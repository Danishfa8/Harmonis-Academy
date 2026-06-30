<?php

namespace App\Http\Controllers;

use App\Models\PenilaianSkill;
use App\Models\PaketKursus;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PenilaianSkillController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $paketFilter = $request->get('paket_kursus_id');

        $penilaian = PenilaianSkill::with('peserta.paketKursus')
            ->when($search, fn($q) => $q->whereHas('peserta', fn($sq) => $sq->where('nama', 'like', "%{$search}%")))
            ->when($paketFilter, fn($q) => $q->whereHas('peserta', fn($sq) => $sq->where('paket_kursus_id', $paketFilter)))
            ->latest()->get();

        $pesertaTanpaNilai = Peserta::whereDoesntHave('penilaianSkill')->orderBy('nama')->get();
        $paketKursus = PaketKursus::where('status', 'aktif')->orderBy('nama_paket')->get();

        return view('penilaian-skill.index', compact('penilaian', 'pesertaTanpaNilai', 'paketKursus', 'search', 'paketFilter'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'peserta_id' => 'required|exists:peserta,id|unique:penilaian_skill,peserta_id',
            'cutting' => 'required|integer|min:0|max:100',
            'styling' => 'required|integer|min:0|max:100',
            'coloring' => 'required|integer|min:0|max:100',
            'shaving' => 'required|integer|min:0|max:100',
            'hygiene' => 'required|integer|min:0|max:100',
            'tanggal_penilaian' => 'required|date',
        ]);

        $peserta = Peserta::findOrFail($request->peserta_id);
        if ($peserta->status_bayar !== 'lunas') {
            return back()->with('error', 'Peserta belum lunas tidak dapat diberi penilaian!');
        }

        PenilaianSkill::create($v);
        return back()->with('success', 'Penilaian skill berhasil ditambahkan!');
    }

    public function update(Request $request, PenilaianSkill $penilaianSkill)
    {
        $v = $request->validate([
            'cutting' => 'required|integer|min:0|max:100',
            'styling' => 'required|integer|min:0|max:100',
            'coloring' => 'required|integer|min:0|max:100',
            'shaving' => 'required|integer|min:0|max:100',
            'hygiene' => 'required|integer|min:0|max:100',
            'tanggal_penilaian' => 'required|date',
        ]);
        $penilaianSkill->update($v);
        return back()->with('success', 'Penilaian skill berhasil diperbarui!');
    }

    public function destroy(PenilaianSkill $penilaianSkill)
    {
        $penilaianSkill->delete();
        return back()->with('success', 'Penilaian skill berhasil dihapus!');
    }
}

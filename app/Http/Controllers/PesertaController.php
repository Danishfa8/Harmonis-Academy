<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\PaketKursus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $peserta = Peserta::with('paketKursus')
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"))
            ->latest()->paginate(10);
        $paketKursus = PaketKursus::where('status', 'aktif')->get();

        return view('peserta.index', compact('peserta', 'search', 'paketKursus'));
    }

    public function show(Peserta $pesertum)
    {
        $pesertum->load('paketKursus', 'penilaianSkill');
        return view('peserta.show', ['peserta' => $pesertum]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:peserta,nik|max:20',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tgl_masuk' => 'required|date',
            'status_bayar' => 'required|in:lunas,belum_lunas',
            'biaya_pengajar' => 'required|numeric|min:0',
            'paket_kursus_id' => 'nullable|exists:paket_kursus,id',
            'deskripsi' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'asal_peserta' => 'nullable|string|max:255',
            'status_pendaftaran' => 'nullable|in:aktif,selesai',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ktp_sim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Default status
        $v['status_pendaftaran'] = $v['status_pendaftaran'] ?? 'aktif';

        // Handle file uploads
        if ($request->hasFile('foto')) {
            $v['foto'] = $request->file('foto')->store('peserta/foto', 'public');
        }
        if ($request->hasFile('ktp_sim')) {
            $v['ktp_sim'] = $request->file('ktp_sim')->store('peserta/ktp', 'public');
        }

        $peserta = Peserta::create($v);

        // Auto-create user account (password = NIK)
        $email = $v['email'] ?? $v['nik'] . '@barber.local';
        User::create([
            'name'       => $peserta->nama,
            'email'      => $email,
            'password'   => Hash::make($peserta->nik),
            'role'       => 'peserta',
            'status'     => 'active',
            'peserta_id' => $peserta->id,
        ]);

        return back()->with('success', 'Peserta berhasil ditambahkan! (Password login = NIK)');
    }

    public function update(Request $request, Peserta $pesertum)
    {
        $p = $pesertum;
        $v = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:peserta,nik,' . $p->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tgl_masuk' => 'required|date',
            'status_bayar' => 'required|in:lunas,belum_lunas',
            'biaya_pengajar' => 'required|numeric|min:0',
            'paket_kursus_id' => 'nullable|exists:paket_kursus,id',
            'deskripsi' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'asal_peserta' => 'nullable|string|max:255',
            'status_pendaftaran' => 'nullable|in:aktif,selesai',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ktp_sim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $v['status_pendaftaran'] = $v['status_pendaftaran'] ?? $p->status_pendaftaran;

        if ($request->hasFile('foto')) {
            if ($p->foto) Storage::disk('public')->delete($p->foto);
            $v['foto'] = $request->file('foto')->store('peserta/foto', 'public');
        }
        if ($request->hasFile('ktp_sim')) {
            if ($p->ktp_sim) Storage::disk('public')->delete($p->ktp_sim);
            $v['ktp_sim'] = $request->file('ktp_sim')->store('peserta/ktp', 'public');
        }

        $p->update($v);
        return back()->with('success', 'Data peserta berhasil diperbarui!');
    }

    public function destroy(Peserta $pesertum)
    {
        if ($pesertum->foto) Storage::disk('public')->delete($pesertum->foto);
        if ($pesertum->ktp_sim) Storage::disk('public')->delete($pesertum->ktp_sim);
        // Delete linked user account
        User::where('peserta_id', $pesertum->id)->delete();
        $pesertum->delete();
        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    public function exportPdf(Peserta $pesertum)
    {
        $pesertum->load('paketKursus');

        // Encode foto for PDF
        $fotoBase64 = null;
        if ($pesertum->foto && Storage::disk('public')->exists($pesertum->foto)) {
            $path = Storage::disk('public')->path($pesertum->foto);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $fotoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $ktpBase64 = null;
        if ($pesertum->ktp_sim && Storage::disk('public')->exists($pesertum->ktp_sim)) {
            $path = Storage::disk('public')->path($pesertum->ktp_sim);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $ktpBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $pdf = Pdf::loadView('peserta.pdf', [
            'peserta' => $pesertum,
            'fotoBase64' => $fotoBase64,
            'ktpBase64' => $ktpBase64,
        ]);
        return $pdf->stream("data-peserta-{$pesertum->nik}.pdf");
    }
}

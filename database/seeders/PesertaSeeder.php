<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Ahmad Rizky', 'nik' => '3201234567890001', 'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No.10, Jakarta', 'tgl_masuk' => '2024-11-15',
                'status_bayar' => 'lunas', 'biaya_pengajar' => 500000, 'paket_kursus_id' => 2,
                'deskripsi' => 'Sangat memuaskan, instruktur profesional', 'email' => 'ahmad@email.com',
                'whatsapp' => '081234567890', 'asal_peserta' => 'Jakarta', 'status_pendaftaran' => 'selesai',
            ],
            [
                'nama' => 'Budi Santoso', 'nik' => '3201234567890002', 'no_hp' => '082345678901',
                'alamat' => 'Jl. Sudirman No.5, Bandung', 'tgl_masuk' => '2025-01-10',
                'status_bayar' => 'belum_lunas', 'biaya_pengajar' => 300000, 'paket_kursus_id' => 1,
                'deskripsi' => 'Cukup baik', 'email' => 'budi@email.com',
                'whatsapp' => '082345678901', 'asal_peserta' => 'Bandung', 'status_pendaftaran' => 'aktif',
            ],
            [
                'nama' => 'Candra Wijaya', 'nik' => '3201234567890003', 'no_hp' => '083456789012',
                'alamat' => 'Jl. Gatot Subroto No.20, Surabaya', 'tgl_masuk' => '2025-03-01',
                'status_bayar' => 'lunas', 'biaya_pengajar' => 500000, 'paket_kursus_id' => 2,
                'deskripsi' => 'Sangat puas dengan pelatihannya', 'email' => 'candra@email.com',
                'whatsapp' => '083456789012', 'asal_peserta' => 'Surabaya', 'status_pendaftaran' => 'selesai',
            ],
            [
                'nama' => 'Deni Firmansyah', 'nik' => '3201234567890004', 'no_hp' => '084567890123',
                'alamat' => 'Jl. Asia Afrika No.8, Semarang', 'tgl_masuk' => '2024-09-20',
                'status_bayar' => 'lunas', 'biaya_pengajar' => 0, 'paket_kursus_id' => 3,
                'deskripsi' => 'Gratis dan berkualitas', 'email' => 'deni@email.com',
                'whatsapp' => '084567890123', 'asal_peserta' => 'Semarang', 'status_pendaftaran' => 'selesai',
            ],
            [
                'nama' => 'Eko Prasetyo', 'nik' => '3201234567890005', 'no_hp' => '085678901234',
                'alamat' => 'Jl. Diponegoro No.15, Yogyakarta', 'tgl_masuk' => '2025-05-01',
                'status_bayar' => 'belum_lunas', 'biaya_pengajar' => 300000, 'paket_kursus_id' => 1,
                'deskripsi' => null, 'email' => 'eko@email.com',
                'whatsapp' => '085678901234', 'asal_peserta' => 'Yogyakarta', 'status_pendaftaran' => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            $peserta = Peserta::create($item);

            // Auto-create user account for each peserta (password = NIK)
            User::create([
                'name'       => $peserta->nama,
                'email'      => $peserta->email,
                'password'   => Hash::make($peserta->nik),
                'role'       => 'peserta',
                'status'     => 'active',
                'peserta_id' => $peserta->id,
            ]);
        }
    }
}

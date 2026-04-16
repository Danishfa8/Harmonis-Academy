<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketKursus;

class PaketKursusSeeder extends Seeder
{
    public function run(): void
    {
        PaketKursus::insert([
            [
                'nama_paket' => 'Paket Basic',
                'deskripsi' => 'Pelatihan dasar teknik barbershop mencakup potong rambut dasar, fade basic, dan perawatan alat.',
                'kategori' => 'berbayar',
                'harga' => 300000,
                'lama_pelatihan' => '3 Bulan',
                'status' => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Advanced',
                'deskripsi' => 'Pelatihan lanjutan meliputi teknik fade advanced, hair design, coloring, dan shaving profesional.',
                'kategori' => 'berbayar',
                'harga' => 500000,
                'lama_pelatihan' => '6 Bulan',
                'status' => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Pemerintah',
                'deskripsi' => 'Program pelatihan gratis dari pemerintah untuk peserta yang memenuhi syarat.',
                'kategori' => 'gratis',
                'harga' => 0,
                'lama_pelatihan' => '4 Bulan',
                'status' => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

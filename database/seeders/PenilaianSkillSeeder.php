<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenilaianSkill;

class PenilaianSkillSeeder extends Seeder
{
    public function run(): void
    {
        PenilaianSkill::insert([
            [
                'peserta_id' => 1, 'cutting' => 85, 'styling' => 90, 'coloring' => 80,
                'shaving' => 88, 'hygiene' => 95, 'ulasan_instruktur' => 'Sangat berbakat dalam styling',
                'tanggal_penilaian' => '2025-03-15', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'peserta_id' => 2, 'cutting' => 70, 'styling' => 65, 'coloring' => 60,
                'shaving' => 72, 'hygiene' => 80, 'ulasan_instruktur' => 'Perlu latihan lebih untuk coloring',
                'tanggal_penilaian' => '2025-03-15', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'peserta_id' => 3, 'cutting' => 90, 'styling' => 85, 'coloring' => 88,
                'shaving' => 92, 'hygiene' => 90, 'ulasan_instruktur' => 'Konsisten dan cepat belajar',
                'tanggal_penilaian' => '2025-03-15', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'peserta_id' => 4, 'cutting' => 75, 'styling' => 70, 'coloring' => 65,
                'shaving' => 78, 'hygiene' => 85, 'ulasan_instruktur' => 'Progres bagus',
                'tanggal_penilaian' => '2025-03-15', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'peserta_id' => 5, 'cutting' => 50, 'styling' => 45, 'coloring' => 40,
                'shaving' => 55, 'hygiene' => 70, 'ulasan_instruktur' => 'Baru mulai, perlu bimbingan',
                'tanggal_penilaian' => '2025-03-15', 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

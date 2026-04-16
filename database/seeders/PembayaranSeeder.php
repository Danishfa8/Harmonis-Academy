<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        Pembayaran::insert([
            // 2024-2025 historical data
            ['peserta_id'=>1,'tanggal'=>'2024-11-15','metode'=>'transfer','nominal'=>500000,'status'=>'valid','keterangan'=>'Pembayaran penuh Paket Advanced','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>2,'tanggal'=>'2025-01-10','metode'=>'cash','nominal'=>150000,'status'=>'valid','keterangan'=>'DP Paket Basic','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>3,'tanggal'=>'2025-03-01','metode'=>'transfer','nominal'=>500000,'status'=>'valid','keterangan'=>'Pembayaran penuh Paket Advanced','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>4,'tanggal'=>'2024-09-20','metode'=>'cash','nominal'=>0,'status'=>'valid','keterangan'=>'Paket Pemerintah - Gratis','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>5,'tanggal'=>'2025-05-01','metode'=>'transfer','nominal'=>100000,'status'=>'valid','keterangan'=>'DP Paket Basic','created_at'=>now(),'updated_at'=>now()],

            // 2026 data — fills dashboard chart
            ['peserta_id'=>1,'tanggal'=>'2026-01-05','metode'=>'transfer','nominal'=>750000,'status'=>'valid','keterangan'=>'Pembayaran Paket Advanced Q1','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>2,'tanggal'=>'2026-01-18','metode'=>'cash','nominal'=>300000,'status'=>'valid','keterangan'=>'Cicilan Paket Basic','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>3,'tanggal'=>'2026-02-10','metode'=>'transfer','nominal'=>500000,'status'=>'valid','keterangan'=>'Paket Advanced full','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>1,'tanggal'=>'2026-02-25','metode'=>'cash','nominal'=>250000,'status'=>'valid','keterangan'=>'Biaya sertifikasi','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>5,'tanggal'=>'2026-03-03','metode'=>'transfer','nominal'=>300000,'status'=>'valid','keterangan'=>'Paket Basic full','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>2,'tanggal'=>'2026-03-15','metode'=>'cash','nominal'=>450000,'status'=>'valid','keterangan'=>'Upgrade ke Advanced','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>4,'tanggal'=>'2026-03-20','metode'=>'transfer','nominal'=>200000,'status'=>'valid','keterangan'=>'Workshop privat','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>3,'tanggal'=>'2026-04-05','metode'=>'transfer','nominal'=>600000,'status'=>'valid','keterangan'=>'Paket Advanced renewal','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>1,'tanggal'=>'2026-04-10','metode'=>'cash','nominal'=>350000,'status'=>'valid','keterangan'=>'Pelatihan tambahan','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>5,'tanggal'=>'2026-05-01','metode'=>'transfer','nominal'=>300000,'status'=>'valid','keterangan'=>'Cicilan Paket Basic','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>2,'tanggal'=>'2026-05-20','metode'=>'cash','nominal'=>500000,'status'=>'valid','keterangan'=>'Pembayaran penuh','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>3,'tanggal'=>'2026-06-08','metode'=>'transfer','nominal'=>400000,'status'=>'valid','keterangan'=>'Sertifikasi lanjutan','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>1,'tanggal'=>'2026-06-15','metode'=>'cash','nominal'=>250000,'status'=>'valid','keterangan'=>'Workshop coloring','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>4,'tanggal'=>'2026-07-02','metode'=>'transfer','nominal'=>350000,'status'=>'valid','keterangan'=>'Pelatihan shaving','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>5,'tanggal'=>'2026-07-18','metode'=>'cash','nominal'=>200000,'status'=>'valid','keterangan'=>'Cicilan ke-3','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>2,'tanggal'=>'2026-08-05','metode'=>'transfer','nominal'=>450000,'status'=>'valid','keterangan'=>'Paket Advanced','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>3,'tanggal'=>'2026-08-22','metode'=>'cash','nominal'=>300000,'status'=>'valid','keterangan'=>'Workshop styling','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>1,'tanggal'=>'2026-09-10','metode'=>'transfer','nominal'=>550000,'status'=>'valid','keterangan'=>'Renewal + sertifikasi','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>4,'tanggal'=>'2026-09-25','metode'=>'cash','nominal'=>150000,'status'=>'valid','keterangan'=>'Workshop hygiene','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>5,'tanggal'=>'2026-10-08','metode'=>'transfer','nominal'=>300000,'status'=>'valid','keterangan'=>'Pembayaran termin','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>2,'tanggal'=>'2026-10-20','metode'=>'cash','nominal'=>400000,'status'=>'valid','keterangan'=>'Pelatihan cutting','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>3,'tanggal'=>'2026-11-05','metode'=>'transfer','nominal'=>500000,'status'=>'valid','keterangan'=>'Paket Advanced Q4','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>1,'tanggal'=>'2026-11-18','metode'=>'cash','nominal'=>250000,'status'=>'valid','keterangan'=>'Workshop akhir tahun','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>4,'tanggal'=>'2026-12-03','metode'=>'transfer','nominal'=>350000,'status'=>'valid','keterangan'=>'Pelatihan akhir tahun','created_at'=>now(),'updated_at'=>now()],
            ['peserta_id'=>5,'tanggal'=>'2026-12-15','metode'=>'cash','nominal'=>200000,'status'=>'valid','keterangan'=>'Cicilan terakhir','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}

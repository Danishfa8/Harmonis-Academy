<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('no_hp');
            $table->text('alamat');
            $table->date('tgl_masuk');
            $table->enum('status_bayar', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->decimal('biaya_pengajar', 12, 2)->default(0);
            $table->foreignId('paket_kursus_id')->nullable()->constrained('paket_kursus')->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->string('ktp_sim')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('asal_peserta')->nullable();
            $table->enum('status_pendaftaran', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};

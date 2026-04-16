<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->unsignedTinyInteger('cutting')->default(0);
            $table->unsignedTinyInteger('styling')->default(0);
            $table->unsignedTinyInteger('coloring')->default(0);
            $table->unsignedTinyInteger('shaving')->default(0);
            $table->unsignedTinyInteger('hygiene')->default(0);
            $table->text('ulasan_instruktur')->nullable();
            $table->date('tanggal_penilaian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_skill');
    }
};

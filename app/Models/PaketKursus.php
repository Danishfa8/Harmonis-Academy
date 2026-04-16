<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaketKursus extends Model
{
    protected $table = 'paket_kursus';

    protected $fillable = [
        'nama_paket', 'deskripsi', 'kategori', 'harga', 'lama_pelatihan', 'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'paket_kursus_id');
    }
}

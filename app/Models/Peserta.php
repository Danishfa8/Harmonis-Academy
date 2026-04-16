<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peserta extends Model
{
    protected $table = 'peserta';

    protected $fillable = [
        'nama', 'nik', 'no_hp', 'alamat', 'tgl_masuk', 'status_bayar',
        'biaya_pengajar', 'paket_kursus_id', 'deskripsi', 'foto', 'ktp_sim',
        'email', 'whatsapp', 'asal_peserta', 'status_pendaftaran',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'biaya_pengajar' => 'decimal:2',
    ];

    public function paketKursus(): BelongsTo
    {
        return $this->belongsTo(PaketKursus::class, 'paket_kursus_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'peserta_id');
    }

    public function penilaianSkill(): HasOne
    {
        return $this->hasOne(PenilaianSkill::class, 'peserta_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'peserta_id');
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->nama, 0, 1));
    }
}

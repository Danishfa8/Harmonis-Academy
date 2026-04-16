<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianSkill extends Model
{
    protected $table = 'penilaian_skill';

    protected $fillable = [
        'peserta_id', 'cutting', 'styling', 'coloring', 'shaving', 'hygiene',
        'ulasan_instruktur', 'tanggal_penilaian',
    ];

    protected $casts = [
        'tanggal_penilaian' => 'date',
        'cutting' => 'integer',
        'styling' => 'integer',
        'coloring' => 'integer',
        'shaving' => 'integer',
        'hygiene' => 'integer',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function getRataRataAttribute(): float
    {
        return round(($this->cutting + $this->styling + $this->coloring + $this->shaving + $this->hygiene) / 5, 0);
    }

    public function getGradeAttribute(): string
    {
        $avg = $this->rata_rata;
        if ($avg >= 85) return 'A';
        if ($avg >= 75) return 'B';
        if ($avg >= 60) return 'C';
        if ($avg >= 50) return 'D';
        return 'E';
    }
}

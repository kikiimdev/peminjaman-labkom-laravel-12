<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeliharaanRuangan extends Model
{
    protected $fillable = [
        'ruangan_id',
        'judul',
        'deskripsi',
        'dijadwalkan_pada',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'dijadwalkan_pada' => 'datetime',
        ];
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function scopeByRuangan($query, $ruanganId)
    {
        return $query->where('ruangan_id', $ruanganId);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'TERJADWAL' => 'Terjadwal',
            'SEDANG_BERJALAN' => 'Sedang Berjalan',
            'SELESAI' => 'Selesai',
            'DIBATALKAN' => 'Dibatalkan',
            default => $this->status,
        };
    }
}

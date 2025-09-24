<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanRuangan extends Model
{
    protected $fillable = [
        'jadwal_id',
        'ruangan_id',
        'petugas_id',
        'kondisi',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }

    public function scopeByRuangan($query, $ruanganId)
    {
        return $query->where('ruangan_id', $ruanganId);
    }

    public function scopeByPetugas($query, $petugasId)
    {
        return $query->where('petugas_id', $petugasId);
    }

    public function scopeByKondisi($query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest('created_at');
    }

    public function isGood(): bool
    {
        return $this->kondisi === 'BAIK';
    }

    public function needsMaintenance(): bool
    {
        return $this->kondisi === 'BUTUH_PERBAIKAN';
    }

    public function isDamaged(): bool
    {
        return $this->kondisi === 'RUSAK';
    }

    public function getKondisiLabel(): string
    {
        return match ($this->kondisi) {
            'BAIK' => 'Baik',
            'BUTUH_PERBAIKAN' => 'Butuh Perbaikan',
            'RUSAK' => 'Rusak',
            default => 'Tidak Diketahui',
        };
    }

    public function getKondisiColor(): string
    {
        return match ($this->kondisi) {
            'BAIK' => 'green',
            'BUTUH_PERBAIKAN' => 'yellow',
            'RUSAK' => 'red',
            default => 'gray',
        };
    }
}

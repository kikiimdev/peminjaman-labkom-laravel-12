<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'keperluan',
        'status',
        'peminjam_id',
        'ruangan_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function tanggalJadwals()
    {
        return $this->hasMany(TanggalJadwal::class);
    }

    public function persetujuanJadwals()
    {
        return $this->hasMany(PersetujuanJadwal::class);
    }

    public function riwayatStatusJadwals()
    {
        return $this->hasMany(RiwayatStatusJadwal::class);
    }

    public function pemeriksaanRuangans()
    {
        return $this->hasMany(PemeriksaanRuangan::class);
    }

    public function insidens()
    {
        return $this->hasMany(Insiden::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'DISETUJUI');
    }

    public function scopeByRuangan($query, $ruanganId)
    {
        return $query->where('ruangan_id', $ruanganId);
    }

    public function scopeBetweenDate($query, $startDate, $endDate)
    {
        return $query->whereHas('tanggalJadwals', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate]);
        });
    }
}

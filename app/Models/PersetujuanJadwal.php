<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanJadwal extends Model
{
    protected $fillable = [
        'jadwal_id',
        'aktor_id',
        'status',
        'catatan',
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

    public function aktor()
    {
        return $this->belongsTo(User::class, 'aktor_id');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }

    public function scopeByAktor($query, $aktorId)
    {
        return $query->where('aktor_id', $aktorId);
    }

    public function isApproved(): bool
    {
        return $this->status === 'DISETUJUI';
    }

    public function isRejected(): bool
    {
        return $this->status === 'DITOLAK';
    }

    public function isPending(): bool
    {
        return $this->status === 'MENUNGGU';
    }
}

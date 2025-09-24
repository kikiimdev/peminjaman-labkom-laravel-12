<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatusJadwal extends Model
{
    protected $fillable = [
        'jadwal_id',
        'dari',
        'menjadi',
        'aktor_id',
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

    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }

    public function scopeByAktor($query, $aktorId)
    {
        return $query->where('aktor_id', $aktorId);
    }

    public function scopeByStatusChange($query, $dari, $menjadi)
    {
        return $query->where('dari', $dari)->where('menjadi', $menjadi);
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest('created_at');
    }

    public function getStatusTransition(): string
    {
        return "{$this->dari} → {$this->menjadi}";
    }

    public function wasApproval(): bool
    {
        return $this->menjadi === 'DISETUJUI';
    }

    public function wasRejection(): bool
    {
        return $this->menjadi === 'DITOLAK';
    }

    public function wasCancellation(): bool
    {
        return $this->menjadi === 'DIBATALKAN';
    }
}

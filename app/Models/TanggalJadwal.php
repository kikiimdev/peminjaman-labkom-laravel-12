<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanggalJadwal extends Model
{
    protected $fillable = [
        'jadwal_id',
        'tanggal',
        'jam_mulai',
        'jam_berakhir',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function getJamMulaiAttribute($value)
    {
        return $this->formatJam($value);
    }

    public function getJamBerakhirAttribute($value)
    {
        return $this->formatJam($value);
    }

    private function formatJam($value)
    {
        if (empty($value)) {
            return null;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('H:i', $timestamp);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function getDurasiAttribute()
    {
        if (empty($this->jam_mulai) || empty($this->jam_berakhir)) {
            return null;
        }

        $mulai = strtotime($this->jam_mulai);
        $akhir = strtotime($this->jam_berakhir);

        if ($mulai === false || $akhir === false) {
            return null;
        }

        if ($akhir < $mulai) {
            $akhir += 24 * 60 * 60; // Tambah 24 jam jika melewati tengah malam
        }

        return ($akhir - $mulai) / 3600; // Konversi ke jam
    }
}

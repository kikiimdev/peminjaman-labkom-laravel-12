<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranJadwal extends Model
{
    protected $fillable = [
        'jadwal_id',
        'nama_file',
        'path_file',
        'tipe',
        'mime_type',
        'ukuran',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'ukuran' => 'integer',
        ];
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function getUkuranFormatAttribute(): string
    {
        $bytes = $this->ukuran;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'SURAT_PENGAJUAN' => 'Surat Pengajuan',
            'DOKUMEN_PENDUKUNG' => 'Dokumen Pendukung',
            'BUKTI_PEMBAYARAN' => 'Bukti Pembayaran',
            'LAINNYA' => 'Lainnya',
            default => $this->tipe,
        };
    }
}

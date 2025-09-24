<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insiden extends Model
{
    protected $fillable = [
        'jadwal_id',
        'ruangan_id',
        'pelapor_id',
        'tingkat',
        'deskripsi',
        'ditangani_oleh',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'selesai_pada' => 'datetime',
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

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    // ditangani_oleh is now a text field, not a relationship

    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }

    public function scopeByRuangan($query, $ruanganId)
    {
        return $query->where('ruangan_id', $ruanganId);
    }

    public function scopeByPelapor($query, $pelaporId)
    {
        return $query->where('pelapor_id', $pelaporId);
    }

    public function scopeByPenanggungJawab($query, $penanggungJawab)
    {
        return $query->where('ditangani_oleh', 'like', '%'.$penanggungJawab.'%');
    }

    public function scopeByTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    public function scopeOpen($query)
    {
        return $query->whereNull('selesai_pada');
    }

    public function scopeClosed($query)
    {
        return $query->whereNotNull('selesai_pada');
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest('created_at');
    }

    public function isOpen(): bool
    {
        return $this->selesai_pada === null;
    }

    public function isClosed(): bool
    {
        return $this->selesai_pada !== null;
    }

    public function isLowSeverity(): bool
    {
        return $this->tingkat === 'RENDAH';
    }

    public function isMediumSeverity(): bool
    {
        return $this->tingkat === 'SEDANG';
    }

    public function isHighSeverity(): bool
    {
        return $this->tingkat === 'TINGGI';
    }

    public function getTingkatLabel(): string
    {
        return match ($this->tingkat) {
            'RENDAH' => 'Rendah',
            'SEDANG' => 'Sedang',
            'TINGGI' => 'Tinggi',
            default => 'Tidak Diketahui',
        };
    }

    public function getTingkatColor(): string
    {
        return match ($this->tingkat) {
            'RENDAH' => 'green',
            'SEDANG' => 'yellow',
            'TINGGI' => 'red',
            default => 'gray',
        };
    }

    public function getDuration(): ?string
    {
        if (! $this->isOpen()) {
            $duration = $this->created_at->diff($this->selesai_pada);

            if ($duration->d > 0) {
                return $duration->d.' hari '.$duration->h.' jam';
            } elseif ($duration->h > 0) {
                return $duration->h.' jam '.$duration->i.' menit';
            } else {
                return $duration->i.' menit';
            }
        }

        return null;
    }

    public function markAsCompleted(?string $penanggungJawab = null): void
    {
        if ($penanggungJawab) {
            $this->ditangani_oleh = $penanggungJawab;
        }
        $this->selesai_pada = now();
        $this->save();
    }
}

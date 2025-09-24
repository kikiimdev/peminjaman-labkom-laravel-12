<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $fillable = [
        'nama',
        'lokasi',
        'pemilik_id',
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function fasilitasRuangans()
    {
        return $this->hasMany(FasilitasRuangan::class);
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_ruangans')
            ->withPivot('jumlah')
            ->withTimestamps();
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function pemeriksaanRuangans()
    {
        return $this->hasMany(PemeriksaanRuangan::class);
    }

    public function insidens()
    {
        return $this->hasMany(Insiden::class);
    }

    public function pemeliharaanRuangans()
    {
        return $this->hasMany(PemeliharaanRuangan::class);
    }
}

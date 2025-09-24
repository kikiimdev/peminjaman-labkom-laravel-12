<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $fillable = [
        'nama',
        'satuan',
    ];

    public function fasilitasRuangans()
    {
        return $this->hasMany(FasilitasRuangan::class);
    }

    public function ruangans()
    {
        return $this->belongsToMany(Ruangan::class, 'fasilitas_ruangans')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}

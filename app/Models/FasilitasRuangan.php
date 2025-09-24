<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasRuangan extends Model
{
    protected $fillable = [
        'ruangan_id',
        'fasilitas_id',
        'jumlah',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}

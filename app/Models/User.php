<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nama',
        'nomor_whatsapp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ruangans()
    {
        return $this->hasMany(Ruangan::class, 'pemilik_id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'peminjam_id');
    }

    public function persetujuanJadwals()
    {
        return $this->hasMany(PersetujuanJadwal::class, 'aktor_id');
    }

    public function riwayatStatusJadwals()
    {
        return $this->hasMany(RiwayatStatusJadwal::class, 'aktor_id');
    }

    public function pemeriksaanRuangans()
    {
        return $this->hasMany(PemeriksaanRuangan::class, 'petugas_id');
    }

    public function insidens()
    {
        return $this->hasMany(Insiden::class, 'pelapor_id');
    }

    public function insidensDitangani()
    {
        return $this->hasMany(Insiden::class, 'ditangani_oleh');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's name (use nama field if name is empty)
     */
    public function getNameAttribute($value)
    {
        return $value ?: $this->nama;
    }

    /**
     * Set the user's name (also update nama field)
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['nama'] = $value;
    }
}

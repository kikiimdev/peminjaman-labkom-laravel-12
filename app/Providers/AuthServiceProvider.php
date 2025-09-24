<?php

namespace App\Providers;

use App\Models\Fasilitas;
use App\Models\Insiden;
use App\Models\Jadwal;
use App\Models\LampiranJadwal;
use App\Models\PemeriksaanRuangan;
use App\Models\PemeliharaanRuangan;
use App\Models\PersetujuanJadwal;
use App\Models\RiwayatStatusJadwal;
use App\Models\Ruangan;
use App\Policies\FasilitasPolicy;
use App\Policies\InsidenPolicy;
use App\Policies\JadwalPolicy;
use App\Policies\LampiranJadwalPolicy;
use App\Policies\PemeriksaanRuanganPolicy;
use App\Policies\PemeliharaanRuanganPolicy;
use App\Policies\PersetujuanJadwalPolicy;
use App\Policies\RiwayatStatusJadwalPolicy;
use App\Policies\RuanganPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Fasilitas::class => FasilitasPolicy::class,
        Insiden::class => InsidenPolicy::class,
        Jadwal::class => JadwalPolicy::class,
        LampiranJadwal::class => LampiranJadwalPolicy::class,
        PemeriksaanRuangan::class => PemeriksaanRuanganPolicy::class,
        PemeliharaanRuangan::class => PemeliharaanRuanganPolicy::class,
        PersetujuanJadwal::class => PersetujuanJadwalPolicy::class,
        RiwayatStatusJadwal::class => RiwayatStatusJadwalPolicy::class,
        Ruangan::class => RuanganPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}

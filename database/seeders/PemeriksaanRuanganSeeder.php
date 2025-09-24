<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\PemeriksaanRuangan;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PemeriksaanRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing inspections
        PemeriksaanRuangan::query()->delete();

        // Get admin user, schedules, and rooms
        $admin = User::where('role', 'ADMIN')->first();
        $jadwals = Jadwal::all();
        $ruangans = Ruangan::all();

        if (! $admin || $jadwals->isEmpty() || $ruangans->isEmpty()) {
            $this->command->error('Required data not found. Please run UserSeeder, JadwalSeeder, and RuanganSeeder first.');

            return;
        }

        // Create sample inspection records for rooms
        $pemeriksaans = [];
        $kondisiOptions = ['BAIK', 'BUTUH_PERBAIKAN', 'RUSAK'];

        // Create inspections for each room
        foreach ($ruangans as $ruangan) {
            $sampleJadwal = $jadwals->random();
            $kondisi = $kondisiOptions[array_rand($kondisiOptions)];

            $pemeriksaans[] = [
                'jadwal_id' => $sampleJadwal->id,
                'ruangan_id' => $ruangan->id,
                'petugas_id' => $admin->id,
                'kondisi' => $kondisi,
            ];
        }

        // Create additional random inspections
        for ($i = 0; $i < 20; $i++) {
            $jadwal = $jadwals->random();
            $ruangan = $ruangans->random();
            $kondisi = $kondisiOptions[array_rand($kondisiOptions)];

            $pemeriksaans[] = [
                'jadwal_id' => $jadwal->id,
                'ruangan_id' => $ruangan->id,
                'petugas_id' => $admin->id,
                'kondisi' => $kondisi,
            ];
        }

        // Insert all inspection records
        foreach ($pemeriksaans as $pemeriksaanData) {
            $pemeriksaanData['created_at'] = Carbon::now('Asia/Makassar');
            $pemeriksaanData['updated_at'] = Carbon::now('Asia/Makassar');
            PemeriksaanRuangan::create($pemeriksaanData);
        }

        $this->command->info('PemeriksaanRuangan seeded successfully!');
        $this->command->info('Total inspection records created: '.PemeriksaanRuangan::count());
    }
}

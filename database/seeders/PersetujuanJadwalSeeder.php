<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\PersetujuanJadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PersetujuanJadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing approvals
        PersetujuanJadwal::query()->delete();

        // Get admin user and schedules
        $admin = User::where('role', 'ADMIN')->first();
        $jadwals = Jadwal::all();

        if (! $admin || $jadwals->isEmpty()) {
            $this->command->error('Admin user or schedules not found. Please run UserSeeder and JadwalSeeder first.');

            return;
        }

        // Create approval records for sample schedules
        $persetujuans = [];
        $sampleJadwals = $jadwals->take(10); // Take first 10 schedules for sample approvals

        foreach ($sampleJadwals as $index => $jadwal) {
            $statusOptions = ['MENUNGGU', 'DISETUJUI', 'DITOLAK'];
            $status = $statusOptions[array_rand($statusOptions)];

            $catatan = '';
            switch ($status) {
                case 'MENUNGGU':
                    $catatan = 'Menunggu persetujuan dari admin';
                    break;
                case 'DISETUJUI':
                    $catatan = 'Disetujui untuk '.strtolower($jadwal->keperluan).'. Ruang tersedia pada jadwal yang diminta.';
                    break;
                case 'DITOLAK':
                    $catatan = 'Ditolak karena bentrok dengan jadwal lain. Silakan ajukan jadwal lain.';
                    break;
            }

            $persetujuans[] = [
                'jadwal_id' => $jadwal->id,
                'aktor_id' => $admin->id,
                'status' => $status,
                'catatan' => $catatan,
            ];
        }

        // Insert all approval records
        foreach ($persetujuans as $persetujuanData) {
            $persetujuanData['created_at'] = Carbon::now('Asia/Makassar');
            $persetujuanData['updated_at'] = Carbon::now('Asia/Makassar');
            PersetujuanJadwal::create($persetujuanData);
        }

        $this->command->info('PersetujuanJadwal seeded successfully!');
        $this->command->info('Total approval records created: '.PersetujuanJadwal::count());
    }
}

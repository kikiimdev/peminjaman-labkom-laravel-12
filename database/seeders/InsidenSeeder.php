<?php

namespace Database\Seeders;

use App\Models\Insiden;
use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InsidenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing incidents
        Insiden::query()->delete();

        // Get users, schedules, and rooms
        $users = User::where('role', 'USER')->get();
        $admin = User::where('role', 'ADMIN')->first();
        $jadwals = Jadwal::all();
        $ruangans = Ruangan::all();

        if ($users->isEmpty() || ! $admin || $jadwals->isEmpty() || $ruangans->isEmpty()) {
            $this->command->error('Required data not found. Please run UserSeeder, JadwalSeeder, and RuanganSeeder first.');

            return;
        }

        // Create sample incident records
        $insidens = [];
        $tingkatOptions = ['RENDAH', 'SEDANG', 'TINGGI'];
        $insidenTypes = [
            'Komputer tidak bisa menyala',
            'Proyektor tidak berfungsi',
            'AC mati',
            'WiFi tidak terhubung',
            'Listrik padam',
            'Pintu ruangan macet',
            'Speaker tidak keluar suara',
            'Meja rusak',
            'Kursi patah',
            'Whiteboard kotor',
        ];

        // Create sample incidents
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $jadwal = $jadwals->random();
            $ruangan = $ruangans->random();
            $tingkat = $tingkatOptions[array_rand($tingkatOptions)];
            $isHandled = rand(0, 1) === 1;

            $insidenData = [
                'jadwal_id' => $jadwal->id,
                'ruangan_id' => $ruangan->id,
                'pelapor_id' => $user->id,
                'tingkat' => $tingkat,
                'ditangani_oleh' => $isHandled ? $admin->id : null,
                'selesai_pada' => $isHandled ? Carbon::now('Asia/Makassar')->subDays(rand(1, 30)) : null,
                'deskripsi' => $insidenTypes[array_rand($insidenTypes)],
            ];

            $insidens[] = $insidenData;
        }

        // Insert all incident records
        foreach ($insidens as $insidenData) {
            $insidenData['created_at'] = Carbon::now('Asia/Makassar');
            $insidenData['updated_at'] = Carbon::now('Asia/Makassar');
            Insiden::create($insidenData);
        }

        $this->command->info('Insiden seeded successfully!');
        $this->command->info('Total incident records created: '.Insiden::count());
    }
}

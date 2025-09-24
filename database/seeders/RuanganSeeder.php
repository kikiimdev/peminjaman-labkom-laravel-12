<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing rooms
        Ruangan::query()->delete();

        // Get admin user as pemilik
        $admin = User::where('role', 'ADMIN')->first();

        if (! $admin) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');

            return;
        }

        $ruangans = [
            [
                'nama' => 'Lab Komputer 1',
                'lokasi' => 'Gedung A Lt. 2',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Lab Komputer 2',
                'lokasi' => 'Gedung A Lt. 3',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Lab Komputer 3',
                'lokasi' => 'Gedung A Lt. 4',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Lab Multimedia',
                'lokasi' => 'Gedung B Lt. 1',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Lab Jaringan',
                'lokasi' => 'Gedung B Lt. 2',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Ruang Meeting 1',
                'lokasi' => 'Gedung C Lt. 1',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Ruang Meeting 2',
                'lokasi' => 'Gedung C Lt. 2',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Ruang Seminar',
                'lokasi' => 'Gedung D Lt. 1',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Ruang Workshop',
                'lokasi' => 'Gedung D Lt. 2',
                'pemilik_id' => $admin->id,
            ],
            [
                'nama' => 'Ruang Audutorium',
                'lokasi' => 'Gedung E Lt. 1',
                'pemilik_id' => $admin->id,
            ],
        ];

        foreach ($ruangans as $ruanganData) {
            $ruanganData['created_at'] = Carbon::now('Asia/Makassar');
            $ruanganData['updated_at'] = Carbon::now('Asia/Makassar');
            Ruangan::create($ruanganData);
        }

        $this->command->info('Ruangan seeded successfully!');
        $this->command->info('Total rooms created: '.Ruangan::count());
    }
}

<?php

namespace Database\Seeders;

use App\Models\PemeliharaanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PemeliharaanRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing maintenance records
        PemeliharaanRuangan::query()->delete();

        // Get all rooms
        $ruangans = Ruangan::all();

        if ($ruangans->isEmpty()) {
            $this->command->error('No rooms found. Please run RuanganSeeder first.');

            return;
        }

        // Create sample maintenance records
        $pemeliharaans = [];
        $statusOptions = ['TERJADWAL', 'SEDANG_BERJALAN', 'SELESAI', 'DIBATALKAN'];
        $maintenanceTypes = [
            'Perawatan Rutin Komputer',
            'Perbaikan Proyektor',
            'Perbaikan AC',
            'Upgrade RAM Komputer',
            'Perawatan Jaringan',
            'Kalibrasi Sound System',
            'Perbaikan Pintu',
            'Pengecatan Ruangan',
            'Perawatan CCTV',
            'Upgrade Sistem Operasi',
        ];

        // Create maintenance records for each room
        foreach ($ruangans as $ruangan) {
            $maintenanceType = $maintenanceTypes[array_rand($maintenanceTypes)];
            $status = $statusOptions[array_rand($statusOptions)];

            $scheduledDate = Carbon::now('Asia/Makassar');

            switch ($status) {
                case 'TERJADWAL':
                    $scheduledDate = $scheduledDate->addDays(rand(1, 30));
                    break;
                case 'SEDANG_BERJALAN':
                    $scheduledDate = $scheduledDate->subDays(rand(1, 5));
                    break;
                case 'SELESAI':
                    $scheduledDate = $scheduledDate->subDays(rand(5, 30));
                    $completedDate = $scheduledDate->copy()->addDays(rand(1, 7));
                    break;
                case 'DIBATALKAN':
                    $scheduledDate = $scheduledDate->subDays(rand(10, 60));
                    break;
            }

            $pemeliharaans[] = [
                'ruangan_id' => $ruangan->id,
                'judul' => $maintenanceType,
                'deskripsi' => 'Deskripsi untuk '.strtolower($maintenanceType).' di ruangan '.$ruangan->nama,
                'status' => $status,
                'dijadwalkan_pada' => $scheduledDate,
            ];
        }

        // Create additional random maintenance records
        for ($i = 0; $i < 20; $i++) {
            $ruangan = $ruangans->random();
            $maintenanceType = $maintenanceTypes[array_rand($maintenanceTypes)];
            $status = $statusOptions[array_rand($statusOptions)];

            $scheduledDate = Carbon::now('Asia/Makassar');

            switch ($status) {
                case 'TERJADWAL':
                    $scheduledDate = $scheduledDate->addDays(rand(1, 90));
                    break;
                case 'SEDANG_BERJALAN':
                    $scheduledDate = $scheduledDate->subDays(rand(1, 10));
                    break;
                case 'SELESAI':
                    $scheduledDate = $scheduledDate->subDays(rand(10, 90));
                    break;
                case 'DIBATALKAN':
                    $scheduledDate = $scheduledDate->subDays(rand(30, 180));
                    break;
            }

            $pemeliharaans[] = [
                'ruangan_id' => $ruangan->id,
                'judul' => $maintenanceType,
                'deskripsi' => 'Deskripsi untuk '.strtolower($maintenanceType).' di ruangan '.$ruangan->nama,
                'status' => $status,
                'dijadwalkan_pada' => $scheduledDate,
            ];
        }

        // Insert all maintenance records
        foreach ($pemeliharaans as $pemeliharaanData) {
            $pemeliharaanData['created_at'] = Carbon::now('Asia/Makassar');
            $pemeliharaanData['updated_at'] = Carbon::now('Asia/Makassar');
            PemeliharaanRuangan::create($pemeliharaanData);
        }

        $this->command->info('PemeliharaanRuangan seeded successfully!');
        $this->command->info('Total maintenance records created: '.PemeliharaanRuangan::count());
    }
}

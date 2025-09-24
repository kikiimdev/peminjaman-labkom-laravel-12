<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\TanggalJadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing schedules
        Jadwal::query()->delete();
        TanggalJadwal::query()->delete();

        // Get users and rooms
        $users = User::where('role', 'USER')->get();
        $ruangans = Ruangan::all();

        if ($users->isEmpty() || $ruangans->isEmpty()) {
            $this->command->error('No users or rooms found. Please run UserSeeder and RuanganSeeder first.');

            return;
        }

        $startDate = Carbon::now('Asia/Makassar')->subYears(1);
        $endDate = Carbon::now('Asia/Makassar')->addYears(1);

        // Schedule purposes
        $keperluans = [
            'Kuliah Pemrograman Web', 'Seminar Teknologi Informasi', 'Workshop Design Grafis',
            'Rapat Departemen', 'Presentasi Proyek', 'Ujian Semester',
            'Praktikum Jaringan', 'Training Karyawan', 'Workshop Digital Marketing',
            'Studi Kasus Manajemen', 'Kuliah Tamu', 'Pelatihan Bahasa Inggris',
            'Rapat Koordinasi', 'Presentasi Startup', 'Workshop Mobile Development',
            'Ujian Tengah Semester', 'Seminar Nasional', 'Workshop Data Science',
            'Rapat Evaluasi', 'Presentasi Riset', 'Workshop UI/UX Design',
            'Kuliah Sistem Operasi', 'Seminar Blockchain', 'Workshop Cloud Computing',
            'Rapat Perencanaan', 'Presentasi Bisnis', 'Workshop Cybersecurity',
            'Ujian Akhir Semester', 'Seminar Machine Learning', 'Workshop DevOps',
        ];

        // Status options
        $statuses = ['DIAJUKAN', 'DISETUJUI', 'DITOLAK', 'DIBATALKAN', 'SELESAI'];
        $statusWeights = [20, 40, 10, 15, 15]; // Weight distribution

        // Time slots
        $timeSlots = [
            ['08:00', '10:00'], ['10:00', '12:00'], ['13:00', '15:00'], ['15:00', '17:00'],
            ['09:00', '11:00'], ['11:00', '13:00'], ['14:00', '16:00'], ['16:00', '18:00'],
        ];

        $this->command->info('Creating 10,000 schedules...');

        // Create first 50 real dummy data manually
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $ruangan = $ruangans->random();
            $keperluan = $keperluans[array_rand($keperluans)];
            $status = $this->getWeightedRandom($statuses, $statusWeights);

            // Create specific date for first 50 (within next 3 months)
            $scheduleDate = Carbon::now('Asia/Makassar')->addDays(rand(1, 90));

            $jadwal = Jadwal::create([
                'keperluan' => $keperluan,
                'status' => $status,
                'peminjam_id' => $user->id,
                'ruangan_id' => $ruangan->id,
                'created_at' => $scheduleDate->copy()->subDays(rand(1, 30)),
                'updated_at' => $scheduleDate->copy()->subDays(rand(0, 29)),
            ]);

            // Create schedule dates
            $timeSlot = $timeSlots[array_rand($timeSlots)];
            TanggalJadwal::create([
                'jadwal_id' => $jadwal->id,
                'tanggal' => $scheduleDate,
                'jam_mulai' => $timeSlot[0],
                'jam_berakhir' => $timeSlot[1],
                'created_at' => $scheduleDate->copy()->subDays(rand(1, 30)),
                'updated_at' => $scheduleDate->copy()->subDays(rand(0, 29)),
            ]);
        }

        // Create remaining 9,950 schedules randomly
        for ($i = 50; $i < 10000; $i++) {
            $user = $users->random();
            $ruangan = $ruangans->random();
            $keperluan = $keperluans[array_rand($keperluans)];
            $status = $this->getWeightedRandom($statuses, $statusWeights);

            // Random date within 2-year interval
            $scheduleDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp),
                'Asia/Makassar'
            );

            $jadwal = Jadwal::create([
                'keperluan' => $keperluan,
                'status' => $status,
                'peminjam_id' => $user->id,
                'ruangan_id' => $ruangan->id,
                'created_at' => $scheduleDate->copy()->subDays(rand(1, 365)),
                'updated_at' => $scheduleDate->copy()->subDays(rand(0, 364)),
            ]);

            // Create schedule dates (sometimes multiple dates for multi-day events)
            $durationDays = rand(1, 3); // 1-3 days
            for ($day = 0; $day < $durationDays; $day++) {
                $eventDate = $scheduleDate->copy()->addDays($day);
                $timeSlot = $timeSlots[array_rand($timeSlots)];

                TanggalJadwal::create([
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $eventDate,
                    'jam_mulai' => $timeSlot[0],
                    'jam_berakhir' => $timeSlot[1],
                    'created_at' => $eventDate->copy()->subDays(rand(1, 365)),
                    'updated_at' => $eventDate->copy()->subDays(rand(0, 364)),
                ]);
            }

            // Progress indicator
            if ($i % 1000 === 0) {
                $this->command->info("Created {$i} schedules...");
            }
        }

        $this->command->info('Jadwal seeded successfully!');
        $this->command->info('Total schedules created: '.Jadwal::count());
        $this->command->info('Total schedule dates created: '.TanggalJadwal::count());
    }

    /**
     * Get random value based on weights
     */
    private function getWeightedRandom(array $values, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $randomWeight = rand(1, $totalWeight);

        $currentWeight = 0;
        foreach ($weights as $index => $weight) {
            $currentWeight += $weight;
            if ($randomWeight <= $currentWeight) {
                return $values[$index];
            }
        }

        return $values[array_rand($values)];
    }
}

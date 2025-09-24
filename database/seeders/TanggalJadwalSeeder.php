<?php

namespace Database\Seeders;

use App\Models\TanggalJadwal;
use Illuminate\Database\Seeder;

class TanggalJadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Note: TanggalJadwal data is now created directly in JadwalSeeder
        // This seeder is kept for compatibility but doesn't create any data

        $this->command->info('TanggalJadwal seeder skipped - data created in JadwalSeeder');
        $this->command->info('Total schedule dates: '.TanggalJadwal::count());
    }
}

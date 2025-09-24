<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding with data reset...');
        $this->command->info('This will clear all existing data and create fresh dummy data.');
        $this->command->info('');

        // Call seeders in correct order to respect foreign key constraints
        $this->call([
            UserSeeder::class,
            RuanganSeeder::class,
            FasilitasSeeder::class,
            FasilitasRuanganSeeder::class,
            JadwalSeeder::class,
            TanggalJadwalSeeder::class,
            PersetujuanJadwalSeeder::class,
            PemeriksaanRuanganSeeder::class,
            InsidenSeeder::class,
            PemeliharaanRuanganSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Database seeding completed successfully!');
        $this->command->info('Created data:');
        $this->command->info('- 50+ Indonesian users with @gmail.com emails');
        $this->command->info('- 10+ rooms with different types');
        $this->command->info('- 15+ facilities');
        $this->command->info('- 10,000+ schedules over 2 years');
        $this->command->info('- Sample approvals, inspections, incidents, and maintenance records');
        $this->command->info('');
        $this->command->info('Admin login: admin@gmail.com / password');
    }
}

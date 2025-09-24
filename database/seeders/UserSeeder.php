<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users
        User::query()->delete();

        // Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
            'nomor_whatsapp' => '081234567890',
            'created_at' => Carbon::now('Asia/Makassar'),
            'updated_at' => Carbon::now('Asia/Makassar'),
        ]);

        // Indonesian names for realistic dummy data
        $indonesianNames = [
            'Ahmad Fauzi', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari', 'Eko Prasetyo',
            'Fitri Handayani', 'Gunawan Wijaya', 'Hani Permata', 'Irfan Hakim', 'Julia Indah',
            'Kurniawan Setiawan', 'Lina Marlina', 'Muhammad Rizki', 'Nurul Hidayah', 'Oscar Wijaya',
            'Putri Ayu', 'Qori Alawiyah', 'Rizki Ahmad', 'Siti Aminah', 'Taufik Hidayat',
            'Umar Bakri', 'Vina Melinda', 'Wahyu Hidayat', 'Xaverius Wijaya', 'Yuni Kartika',
            'Zainal Abidin', 'Aditya Pratama', 'Maya Sari', 'Fajar Nugroho', 'Rina Susanti',
            'Agus Salim', 'Diana Putri', 'Hendra Wijaya', 'Kartika Sari', 'Lestari Indah',
            'Mahmud Ahmad', 'Nurhasanah', 'Prabowo Setiawan', 'Rahma Dewi', 'Samsul Hadi',
            'Tri Wahyuni', 'Utami Sari', 'Vera Susanti', 'Wibowo Prasetyo', 'Yulia Handayani',
            'Zulfiqar Ali', 'Aisyah Putri', 'Bambang Susilo', 'Citra Lestari', 'Dedi Kurniawan',
        ];

        // Create 50 users with Indonesian names and @gmail.com emails
        foreach ($indonesianNames as $index => $name) {
            $emailName = strtolower(str_replace(' ', '', $name));
            $email = $emailName.'@gmail.com';
            $whatsapp = '0812'.str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            User::create([
                'name' => $name,
                'nama' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'USER',
                'nomor_whatsapp' => $whatsapp,
                'created_at' => Carbon::now('Asia/Makassar'),
                'updated_at' => Carbon::now('Asia/Makassar'),
            ]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@gmail.com / password');
        $this->command->info('Total users created: '.User::count());
    }
}

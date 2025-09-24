<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing facilities
        Fasilitas::query()->delete();

        $fasilitas = [
            [
                'nama' => 'Komputer',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Proyektor',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'AC',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Whiteboard',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Internet WiFi',
                'satuan' => 'Koneksi',
            ],
            [
                'nama' => 'Meja',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Kursi',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Speaker',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Printer',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Kamera',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Layar LCD',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Microphone',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Scanner',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Lampu Presentasi',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Kursi Tamu',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Router',
                'satuan' => 'Unit',
            ],
            [
                'nama' => 'Switch',
                'satuan' => 'Unit',
            ],
        ];

        foreach ($fasilitas as $fasilitasData) {
            $fasilitasData['created_at'] = Carbon::now('Asia/Makassar');
            $fasilitasData['updated_at'] = Carbon::now('Asia/Makassar');
            Fasilitas::create($fasilitasData);
        }

        $this->command->info('Fasilitas seeded successfully!');
        $this->command->info('Total facilities created: '.Fasilitas::count());
    }
}

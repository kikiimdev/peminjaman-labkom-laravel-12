<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\FasilitasRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FasilitasRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing room facilities
        FasilitasRuangan::query()->delete();

        // Get all rooms and facilities
        $ruangans = Ruangan::all();
        $fasilitasList = Fasilitas::all();

        if ($ruangans->isEmpty() || $fasilitasList->isEmpty()) {
            $this->command->error('No rooms or facilities found. Please run RuanganSeeder and FasilitasSeeder first.');

            return;
        }

        // Create facility assignments for each room
        $fasilitasAssignments = [];

        // Lab Komputer 1 facilities
        $labKomputer1 = $ruangans->where('nama', 'Lab Komputer 1')->first();
        if ($labKomputer1) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createLabFacilities($labKomputer1, $fasilitasList, 25));
        }

        // Lab Komputer 2 facilities
        $labKomputer2 = $ruangans->where('nama', 'Lab Komputer 2')->first();
        if ($labKomputer2) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createLabFacilities($labKomputer2, $fasilitasList, 30));
        }

        // Lab Komputer 3 facilities
        $labKomputer3 = $ruangans->where('nama', 'Lab Komputer 3')->first();
        if ($labKomputer3) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createLabFacilities($labKomputer3, $fasilitasList, 20));
        }

        // Lab Multimedia facilities
        $labMultimedia = $ruangans->where('nama', 'Lab Multimedia')->first();
        if ($labMultimedia) {
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Komputer')->first()->id,
                'jumlah' => 15,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Layar LCD')->first()->id,
                'jumlah' => 2,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Speaker')->first()->id,
                'jumlah' => 4,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 2,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => 15,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labMultimedia->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => 30,
            ];
        }

        // Lab Jaringan facilities
        $labJaringan = $ruangans->where('nama', 'Lab Jaringan')->first();
        if ($labJaringan) {
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Komputer')->first()->id,
                'jumlah' => 20,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Router')->first()->id,
                'jumlah' => 5,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Switch')->first()->id,
                'jumlah' => 3,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 2,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => 20,
            ];
            $fasilitasAssignments[] = [
                'ruangan_id' => $labJaringan->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => 40,
            ];
        }

        // Meeting rooms facilities
        $meetingRooms = $ruangans->filter(function ($room) {
            return strpos($room->nama, 'Meeting') !== false;
        });

        foreach ($meetingRooms as $meetingRoom) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createMeetingFacilities($meetingRoom, $fasilitasList));
        }

        // Ruang Seminar facilities
        $ruangSeminar = $ruangans->where('nama', 'Ruang Seminar')->first();
        if ($ruangSeminar) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createSeminarFacilities($ruangSeminar, $fasilitasList));
        }

        // Ruang Workshop facilities
        $ruangWorkshop = $ruangans->where('nama', 'Ruang Workshop')->first();
        if ($ruangWorkshop) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createWorkshopFacilities($ruangWorkshop, $fasilitasList));
        }

        // Ruang Audutorium facilities
        $ruangAudutorium = $ruangans->where('nama', 'Ruang Audutorium')->first();
        if ($ruangAudutorium) {
            $fasilitasAssignments = array_merge($fasilitasAssignments, $this->createAuditoriumFacilities($ruangAudutorium, $fasilitasList));
        }

        // Insert all assignments
        foreach ($fasilitasAssignments as $assignment) {
            $assignment['created_at'] = Carbon::now('Asia/Makassar');
            $assignment['updated_at'] = Carbon::now('Asia/Makassar');
            FasilitasRuangan::create($assignment);
        }

        $this->command->info('FasilitasRuangan seeded successfully!');
        $this->command->info('Total room-facility relationships created: '.FasilitasRuangan::count());
    }

    private function createLabFacilities($room, $fasilitasList, $computerCount)
    {
        return [
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Komputer')->first()->id,
                'jumlah' => $computerCount,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Proyektor')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 2,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Whiteboard')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => $computerCount,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => $computerCount * 2,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Printer')->first()->id,
                'jumlah' => 1,
            ],
        ];
    }

    private function createMeetingFacilities($room, $fasilitasList)
    {
        return [
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Proyektor')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Whiteboard')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kamera')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => 10,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Microphone')->first()->id,
                'jumlah' => 2,
            ],
        ];
    }

    private function createSeminarFacilities($room, $fasilitasList)
    {
        return [
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Proyektor')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Layar LCD')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 3,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Whiteboard')->first()->id,
                'jumlah' => 2,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Speaker')->first()->id,
                'jumlah' => 4,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => 20,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => 100,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Microphone')->first()->id,
                'jumlah' => 4,
            ],
        ];
    }

    private function createWorkshopFacilities($room, $fasilitasList)
    {
        return [
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 2,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Whiteboard')->first()->id,
                'jumlah' => 4,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => 10,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => 50,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Lampu Presentasi')->first()->id,
                'jumlah' => 2,
            ],
        ];
    }

    private function createAuditoriumFacilities($room, $fasilitasList)
    {
        return [
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Proyektor')->first()->id,
                'jumlah' => 2,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Layar LCD')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'AC')->first()->id,
                'jumlah' => 5,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Speaker')->first()->id,
                'jumlah' => 8,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Internet WiFi')->first()->id,
                'jumlah' => 1,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Meja')->first()->id,
                'jumlah' => 50,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kursi')->first()->id,
                'jumlah' => 300,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Microphone')->first()->id,
                'jumlah' => 6,
            ],
            [
                'ruangan_id' => $room->id,
                'fasilitas_id' => $fasilitasList->where('nama', 'Kamera')->first()->id,
                'jumlah' => 2,
            ],
        ];
    }
}

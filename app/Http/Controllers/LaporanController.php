<?php

namespace App\Http\Controllers;

use App\Models\Insiden;
use App\Models\Jadwal;
use App\Models\PemeliharaanRuangan;
use App\Models\PemeriksaanRuangan;
use App\Models\PersetujuanJadwal;
use App\Models\RiwayatStatusJadwal;
use App\Models\Ruangan;
use App\Models\TanggalJadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        return view('laporan.index');
    }

    // 1. Rekap Peminjaman per Ruangan & Periode
    public function rekapPeminjaman(Request $request): View
    {
        $query = Jadwal::with(['ruangan', 'peminjam', 'tanggalJadwals']);

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereHas('tanggalJadwals', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jadwals = $query->get();

        // Group by ruangan
        $rekapByRuangan = $jadwals->groupBy('ruangan.nama')->map(function ($group) {
            return [
                'total' => $group->count(),
                'approved' => $group->where('status', 'DISETUJUI')->count(),
                'rejected' => $group->where('status', 'DITOLAK')->count(),
                'pending' => $group->where('status', 'MENUNGGU')->count(),
                'cancelled' => $group->where('status', 'DIBATALKAN')->count(),
                'ruangan' => $group->first()->ruangan,
            ];
        });

        // Group by bulan
        $rekapByBulan = $jadwals->groupBy(function ($jadwal) {
            return $jadwal->created_at->format('Y-m');
        })->map(function ($group) {
            return [
                'total' => $group->count(),
                'approved' => $group->where('status', 'DISETUJUI')->count(),
                'rejected' => $group->where('status', 'DITOLAK')->count(),
                'pending' => $group->where('status', 'MENUNGGU')->count(),
            ];
        });

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.rekap-peminjaman', compact(
            'rekapByRuangan',
            'rekapByBulan',
            'ruangans'
        ));
    }

    // 2. Utilisasi Ruangan (jam terpakai)
    public function utilisasiRuangan(Request $request): View
    {
        $query = TanggalJadwal::with(['jadwal.ruangan'])
            ->whereHas('jadwal', function ($q) {
                $q->where('status', 'DISETUJUI');
            });

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('ruangan_id', $request->ruangan_id);
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $tanggalJadwals = $query->get();

        // Hitung utilisasi per ruangan
        $utilisasiByRuangan = $tanggalJadwals->groupBy('jadwal.ruangan.nama')->map(function ($group) {
            $totalJam = $group->sum('durasi');
            $totalHari = $group->unique('tanggal')->count();

            return [
                'total_jam' => $totalJam,
                'total_hari' => $totalHari,
                'rata_rata_per_hari' => $totalHari > 0 ? $totalJam / $totalHari : 0,
                'ruangan' => $group->first()->jadwal->ruangan,
            ];
        });

        // Hitung jam buka (misal 08:00 - 17:00 = 9 jam)
        $jamBukaPerHari = 9;

        // Hitung total hari dalam rentang tanggal
        $totalHariOperasional = 1;
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $startDate = Carbon::parse($request->tanggal_mulai);
            $endDate = Carbon::parse($request->tanggal_selesai);
            $totalHariOperasional = $startDate->diffInDays($endDate) + 1;
        }

        $kapasitasTotal = $totalHariOperasional * $jamBukaPerHari * Ruangan::count();

        $totalJamTerpakai = $tanggalJadwals->sum('durasi');
        $persentaseUtilisasi = $kapasitasTotal > 0 ? ($totalJamTerpakai / $kapasitasTotal) * 100 : 0;

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.utilisasi-ruangan', compact(
            'utilisasiByRuangan',
            'persentaseUtilisasi',
            'totalJamTerpakai',
            'kapasitasTotal',
            'totalHariOperasional',
            'ruangans'
        ));
    }

    // 3. Status Peminjaman
    public function statusPeminjaman(Request $request): View
    {
        $query = Jadwal::with(['ruangan', 'peminjam']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $jadwals = $query->get();

        $statusCounts = $jadwals->groupBy('status')->map->count();

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.status-peminjaman', compact(
            'statusCounts',
            'jadwals',
            'ruangans'
        ));
    }

    // 4. SLA Persetujuan
    public function slaPersetujuan(Request $request): View
    {
        $query = PersetujuanJadwal::with(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan aktor
        if ($request->filled('aktor_id')) {
            $query->where('aktor_id', $request->aktor_id);
        }

        $persetujuans = $query->get();

        // Hitung SLA (misal 2x24 jam)
        $slaHours = 48;
        $slaResults = [];

        foreach ($persetujuans as $persetujuan) {
            $waktuPengajuan = $persetujuan->jadwal->created_at;
            $waktuPersetujuan = $persetujuan->created_at;
            $selisihJam = $waktuPengajuan->diffInHours($waktuPersetujuan);

            $slaResults[] = [
                'jadwal' => $persetujuan->jadwal,
                'persetujuan' => $persetujuan,
                'waktu_pengajuan' => $waktuPengajuan,
                'waktu_persetujuan' => $waktuPersetujuan,
                'selisih_jam' => $selisihJam,
                'memenuhi_sla' => $selisihJam <= $slaHours,
            ];
        }

        $totalPersetujuan = count($slaResults);
        $memenuhiSla = collect($slaResults)->where('memenuhi_sla', true)->count();
        $tidakMemenuhiSla = $totalPersetujuan - $memenuhiSla;
        $persentaseSla = $totalPersetujuan > 0 ? ($memenuhiSla / $totalPersetujuan) * 100 : 0;

        $aktors = User::orderBy('name')->get();

        return view('laporan.sla-persetujuan', compact(
            'slaResults',
            'totalPersetujuan',
            'memenuhiSla',
            'tidakMemenuhiSla',
            'persentaseSla',
            'slaHours',
            'aktors'
        ));
    }

    // 5. Riwayat Perubahan Status
    public function riwayatPerubahanStatus(Request $request): View
    {
        $query = RiwayatStatusJadwal::with(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan aktor
        if ($request->filled('aktor_id')) {
            $query->where('aktor_id', $request->aktor_id);
        }

        // Filter berdasarkan perubahan status
        if ($request->filled('dari') && $request->filled('menjadi')) {
            $query->where('dari', $request->dari)->where('menjadi', $request->menjadi);
        }

        $riwayatStatus = $query->latest()->get();

        // Group by status change
        $statusChanges = $riwayatStatus->groupBy(function ($item) {
            return $item->dari.' -> '.$item->menjadi;
        })->map(function ($group) {
            return [
                'total' => $group->count(),
                'dari' => $group->first()->dari,
                'menjadi' => $group->first()->menjadi,
            ];
        });

        $aktors = User::orderBy('name')->get();

        return view('laporan.riwayat-perubahan-status', compact(
            'riwayatStatus',
            'statusChanges',
            'aktors'
        ));
    }

    // 6. Pemeriksaan Ruangan (IN/OUT & Kondisi)
    public function pemeriksaanRuangan(Request $request): View
    {
        $query = PemeriksaanRuangan::with(['jadwal.peminjam', 'ruangan.pemilik', 'petugas']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $pemeriksaan = $query->get();

        // Group by kondisi
        $kondisiCounts = $pemeriksaan->groupBy('kondisi')->map->count();

        // Group by ruangan
        $pemeriksaanByRuangan = $pemeriksaan->groupBy('ruangan.nama')->map(function ($group) {
            return [
                'total' => $group->count(),
                'baik' => $group->where('kondisi', 'BAIK')->count(),
                'butuh_perbaikan' => $group->where('kondisi', 'BUTUH_PERBAIKAN')->count(),
                'rusak' => $group->where('kondisi', 'RUSAK')->count(),
                'ruangan' => $group->first()->ruangan,
            ];
        });

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.pemeriksaan-ruangan', compact(
            'pemeriksaan',
            'kondisiCounts',
            'pemeriksaanByRuangan',
            'ruangans'
        ));
    }

    // 7. Insiden per Ruangan/Periode
    public function insiden(Request $request): View
    {
        $query = Insiden::with(['jadwal.peminjam', 'ruangan.pemilik', 'pelapor']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $insidens = $query->get();

        // Group by tingkat
        $tingkatCounts = $insidens->groupBy('tingkat')->map->count();

        // Group by ruangan
        $insidensByRuangan = $insidens->groupBy('ruangan.nama')->map(function ($group) {
            return [
                'total' => $group->count(),
                'rendah' => $group->where('tingkat', 'RENDAH')->count(),
                'sedang' => $group->where('tingkat', 'SEDANG')->count(),
                'tinggi' => $group->where('tingkat', 'TINGGI')->count(),
                'terselesaikan' => $group->whereNotNull('selesai_pada')->count(),
                'belum_terselesaikan' => $group->whereNull('selesai_pada')->count(),
                'ruangan' => $group->first()->ruangan,
            ];
        });

        // Hitung rata-rata waktu penyelesaian
        $waktuPenyelesaian = $insidens->whereNotNull('selesai_pada')->map(function ($insiden) {
            return $insiden->created_at->diffInHours($insiden->selesai_pada);
        });

        $rataRataPenyelesaian = $waktuPenyelesaian->avg();

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.insiden', compact(
            'insidens',
            'tingkatCounts',
            'insidensByRuangan',
            'rataRataPenyelesaian',
            'ruangans'
        ));
    }

    // 8. Pemeliharaan Ruangan
    public function pemeliharaanRuangan(Request $request): View
    {
        $query = PemeliharaanRuangan::with(['ruangan.pemilik']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('dijadwalkan_pada', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pemeliharaan = $query->get();

        // Group by status
        $statusCounts = $pemeliharaan->groupBy('status')->map->count();

        // Group by ruangan
        $pemeliharaanByRuangan = $pemeliharaan->groupBy('ruangan.nama')->map(function ($group) {
            return [
                'total' => $group->count(),
                'terjadwal' => $group->where('status', 'TERJADWAL')->count(),
                'berjalan' => $group->where('status', 'SEDANG_BERJALAN')->count(),
                'selesai' => $group->where('status', 'SELESAI')->count(),
                'dibatalkan' => $group->where('status', 'DIBATALKAN')->count(),
                'total_biaya' => $group->where('status', 'SELESAI')->sum('biaya'),
                'ruangan' => $group->first()->ruangan,
            ];
        });

        // Statistik
        $totalBiaya = $pemeliharaan->where('status', 'SELESAI')->sum('biaya');
        $rataRataBiaya = $pemeliharaan->where('status', 'SELESAI')->avg('biaya');

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.pemeliharaan-ruangan', compact(
            'pemeliharaan',
            'statusCounts',
            'pemeliharaanByRuangan',
            'totalBiaya',
            'rataRataBiaya',
            'ruangans'
        ));
    }

    // 9. Lampiran Pengajuan
    public function lampiranPengajuan(Request $request): View
    {
        $query = Jadwal::with(['ruangan', 'peminjam', 'lampiranJadwals']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $jadwals = $query->get();

        // Statistik lampiran
        $totalPengajuan = $jadwals->count();
        $pengajuanDenganLampiran = $jadwals->where('lampiranJadwals.count', '>', 0)->count();
        $pengajuanTanpaLampiran = $totalPengajuan - $pengajuanDenganLampiran;

        // Group by tipe lampiran
        $lampiranByTipe = $jadwals->flatMap->lampiranJadwals->groupBy('tipe')->map->count();

        // Group by ruangan
        $lampiranByRuangan = $jadwals->groupBy('ruangan.nama')->map(function ($group) {
            return [
                'total_pengajuan' => $group->count(),
                'pengajuan_dengan_lampiran' => $group->where('lampiranJadwals.count', '>', 0)->count(),
                'total_lampiran' => $group->sum('lampiranJadwals.count'),
                'ruangan' => $group->first()->ruangan,
            ];
        });

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('laporan.lampiran-pengajuan', compact(
            'totalPengajuan',
            'pengajuanDenganLampiran',
            'pengajuanTanpaLampiran',
            'lampiranByTipe',
            'lampiranByRuangan',
            'ruangans'
        ));
    }

    // 10. Top Peminjam Aktif
    public function topPeminjam(Request $request): View
    {
        $query = Jadwal::with(['peminjam', 'ruangan']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan status (hanya yang disetujui)
        $query->where('status', 'DISETUJUI');

        $jadwals = $query->get();

        // Group by peminjam
        $peminjamStats = $jadwals->groupBy('peminjam.name')->map(function ($group) {
            $totalJam = $group->flatMap->tanggalJadwals->sum('durasi');
            $uniqueRuangans = $group->pluck('ruangan.nama')->unique()->count();

            return [
                'nama_peminjam' => $group->first()->peminjam->name,
                'email_peminjam' => $group->first()->peminjam->email,
                'total_peminjaman' => $group->count(),
                'total_jam' => $totalJam,
                'unique_ruangans' => $uniqueRuangans,
                'rata_rata_jam_per_peminjaman' => $totalJam / $group->count(),
                'peminjam' => $group->first()->peminjam,
            ];
        })->sortByDesc('total_peminjaman');

        // Top 10 peminjam
        $topPeminjams = $peminjamStats->take(10);

        // Statistik keseluruhan
        $totalPeminjaman = $jadwals->count();
        $totalJam = $jadwals->flatMap->tanggalJadwals->sum('durasi');
        $totalPeminjamUnik = $jadwals->pluck('peminjam_id')->unique()->count();

        return view('laporan.top-peminjam', compact(
            'topPeminjams',
            'totalPeminjaman',
            'totalJam',
            'totalPeminjamUnik'
        ));
    }
}

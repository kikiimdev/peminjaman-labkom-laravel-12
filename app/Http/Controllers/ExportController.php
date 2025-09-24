<?php

namespace App\Http\Controllers;

use App\Exports\GenericExport;
use App\Models\Fasilitas;
use App\Models\Insiden;
use App\Models\Jadwal;
use App\Models\LampiranJadwal;
use App\Models\PemeliharaanRuangan;
use App\Models\PemeriksaanRuangan;
use App\Models\PersetujuanJadwal;
use App\Models\RiwayatStatusJadwal;
use App\Models\Ruangan;
use App\Models\TanggalJadwal;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    // Export Rekap Peminjaman
    public function rekapPeminjaman(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = Jadwal::with(['ruangan', 'peminjam', 'tanggalJadwals']);

        // Apply filters
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereHas('tanggalJadwals', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jadwals = $query->get();

        $data = $jadwals->map(function ($jadwal) {
            return [
                'ID Jadwal' => $jadwal->id,
                'Ruangan' => $jadwal->ruangan->nama,
                'Peminjam' => $jadwal->peminjam->name,
                'Email Peminjam' => $jadwal->peminjam->email,
                'Keperluan' => $jadwal->keperluan,
                'Status' => $jadwal->status,
                'Tanggal Pengajuan' => $jadwal->created_at->format('d/m/Y H:i'),
                'Tanggal Mulai' => $jadwal->tanggalJadwals->min('tanggal'),
                'Tanggal Selesai' => $jadwal->tanggalJadwals->max('tanggal'),
                'Total Jam' => $jadwal->tanggalJadwals->sum('durasi'),
                'Total Hari' => $jadwal->tanggalJadwals->count(),
            ];
        })->toArray();

        $filename = 'rekap_peminjaman_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Jadwal', 'Ruangan', 'Peminjam', 'Email Peminjam',
            'Keperluan', 'Status', 'Tanggal Pengajuan', 'Tanggal Mulai', 'Tanggal Selesai',
            'Total Jam', 'Total Hari',
        ]), $filename);
    }

    // Export Utilisasi Ruangan
    public function utilisasiRuangan(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = TanggalJadwal::with(['jadwal.ruangan'])
            ->whereHas('jadwal', function ($q) {
                $q->where('status', 'DISETUJUI');
            });

        if ($request->filled('ruangan_id')) {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('ruangan_id', $request->ruangan_id);
            });
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $tanggalJadwals = $query->get();

        $data = $tanggalJadwals->map(function ($tanggalJadwal) {
            return [
                'ID Jadwal' => $tanggalJadwal->jadwal_id,
                'Nama Jadwal' => $tanggalJadwal->jadwal->keperluan,
                'Ruangan' => $tanggalJadwal->jadwal->ruangan->nama,
                'Peminjam' => $tanggalJadwal->jadwal->peminjam->name,
                'Tanggal' => $tanggalJadwal->tanggal->format('d/m/Y'),
                'Jam Mulai' => $tanggalJadwal->jam_mulai,
                'Jam Selesai' => $tanggalJadwal->jam_selesai,
                'Durasi (Jam)' => $tanggalJadwal->durasi,
                'Hari' => $tanggalJadwal->tanggal->locale('id')->translatedFormat('l'),
            ];
        })->toArray();

        $filename = 'utilisasi_ruangan_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Jadwal', 'Nama Jadwal', 'Ruangan', 'Peminjam', 'Tanggal',
            'Jam Mulai', 'Jam Selesai', 'Durasi (Jam)', 'Hari',
        ]), $filename);
    }

    // Export Status Peminjaman
    public function statusPeminjaman(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = Jadwal::with(['ruangan', 'peminjam']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $jadwals = $query->get();

        $data = $jadwals->map(function ($jadwal) {
            return [
                'ID Jadwal' => $jadwal->id,
                'Nama Jadwal' => $jadwal->keperluan,
                'Ruangan' => $jadwal->ruangan->nama,
                'Peminjam' => $jadwal->peminjam->name,
                'Status' => $jadwal->status,
                'Tanggal Pengajuan' => $jadwal->created_at->format('d/m/Y H:i'),
                'Total Hari' => $jadwal->tanggalJadwals->count(),
                'Total Jam' => $jadwal->tanggalJadwals->sum('durasi'),
            ];
        })->toArray();

        $filename = 'status_peminjaman_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Jadwal', 'Nama Jadwal', 'Ruangan', 'Peminjam', 'Status',
            'Tanggal Pengajuan', 'Total Hari', 'Total Jam',
        ]), $filename);
    }

    // Export SLA Persetujuan
    public function slaPersetujuan(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', PersetujuanJadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = PersetujuanJadwal::with(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('aktor_id')) {
            $query->where('aktor_id', $request->aktor_id);
        }

        $persetujuans = $query->get();

        $slaHours = 48;
        $data = $persetujuans->map(function ($persetujuan) use ($slaHours) {
            $waktuPengajuan = $persetujuan->jadwal->created_at;
            $waktuPersetujuan = $persetujuan->created_at;
            $selisihJam = $waktuPengajuan->diffInHours($waktuPersetujuan);

            return [
                'ID Jadwal' => $persetujuan->jadwal_id,
                'Nama Jadwal' => $persetujuan->jadwal->keperluan,
                'Ruangan' => $persetujuan->jadwal->ruangan->nama,
                'Peminjam' => $persetujuan->jadwal->peminjam->name,
                'Aktor' => $persetujuan->aktor->name,
                'Waktu Pengajuan' => $waktuPengajuan->format('d/m/Y H:i'),
                'Waktu Persetujuan' => $waktuPersetujuan->format('d/m/Y H:i'),
                'Selisih (Jam)' => $selisihJam,
                'SLA Target (Jam)' => $slaHours,
                'Memenuhi SLA' => $selisihJam <= $slaHours ? 'Ya' : 'Tidak',
                'Aksi' => $persetujuan->aksi,
                'Catatan' => $persetujuan->catatan ?? '-',
            ];
        })->toArray();

        $filename = 'sla_persetujuan_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Jadwal', 'Nama Jadwal', 'Ruangan', 'Peminjam', 'Aktor',
            'Waktu Pengajuan', 'Waktu Persetujuan', 'Selisih (Jam)', 'SLA Target (Jam)',
            'Memenuhi SLA', 'Aksi', 'Catatan',
        ]), $filename);
    }

    // Export Riwayat Perubahan Status
    public function riwayatPerubahanStatus(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', RiwayatStatusJadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = RiwayatStatusJadwal::with(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('aktor_id')) {
            $query->where('aktor_id', $request->aktor_id);
        }

        if ($request->filled('dari') && $request->filled('menjadi')) {
            $query->where('dari', $request->dari)->where('menjadi', $request->menjadi);
        }

        $riwayatStatus = $query->latest()->get();

        $data = $riwayatStatus->map(function ($riwayat) {
            return [
                'ID Riwayat' => $riwayat->id,
                'ID Jadwal' => $riwayat->jadwal_id,
                'Nama Jadwal' => $riwayat->jadwal->keperluan,
                'Ruangan' => $riwayat->jadwal->ruangan->nama,
                'Peminjam' => $riwayat->jadwal->peminjam->name,
                'Aktor' => $riwayat->aktor->name,
                'Status Dari' => $riwayat->dari,
                'Status Menjadi' => $riwayat->menjadi,
                'Tanggal Perubahan' => $riwayat->created_at->format('d/m/Y H:i'),
                'Catatan' => $riwayat->catatan ?? '-',
            ];
        })->toArray();

        $filename = 'riwayat_perubahan_status_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Riwayat', 'ID Jadwal', 'Nama Jadwal', 'Ruangan', 'Peminjam',
            'Aktor', 'Status Dari', 'Status Menjadi', 'Tanggal Perubahan', 'Catatan',
        ]), $filename);
    }

    // Export Pemeriksaan Ruangan
    public function pemeriksaanRuangan(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', PemeriksaanRuangan::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = PemeriksaanRuangan::with(['jadwal.peminjam', 'ruangan', 'petugas']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $pemeriksaan = $query->get();

        $data = $pemeriksaan->map(function ($item) {
            return [
                'ID Pemeriksaan' => $item->id,
                'Nama Jadwal' => $item->jadwal->keperluan,
                'Ruangan' => $item->ruangan->nama,
                'Peminjam' => $item->jadwal->peminjam->name,
                'Petugas' => $item->petugas->name,
                'Kondisi' => $item->kondisi,
                'Tanggal Pemeriksaan' => $item->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $filename = 'pemeriksaan_ruangan_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Pemeriksaan', 'Nama Jadwal', 'Ruangan', 'Peminjam',
            'Petugas', 'Kondisi', 'Tanggal Pemeriksaan',
        ]), $filename);
    }

    // Export Insiden
    public function insiden(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Insiden::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = Insiden::with(['jadwal.peminjam', 'ruangan', 'pelapor']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $insidens = $query->get();

        $data = $insidens->map(function ($insiden) {
            return [
                'ID Insiden' => $insiden->id,
                'Nama Jadwal' => $insiden->jadwal->keperluan,
                'Ruangan' => $insiden->ruangan->nama,
                'Peminjam' => $insiden->jadwal->peminjam->name,
                'Pelapor' => $insiden->pelapor->name,
                'Ditangani Oleh' => $insiden->ditangani_oleh ?? '-',
                'Tingkat' => $insiden->tingkat,
                'Deskripsi' => $insiden->deskripsi,
                'Tanggal Kejadian' => $insiden->created_at->format('d/m/Y H:i'),
                'Tanggal Selesai' => $insiden->selesai_pada ? $insiden->selesai_pada->format('d/m/Y H:i') : '-',
            ];
        })->toArray();

        $filename = 'insiden_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Insiden', 'Nama Jadwal', 'Ruangan', 'Peminjam',
            'Pelapor', 'Penanggung Jawab', 'Tingkat', 'Deskripsi', 'Tanggal Kejadian',
            'Tanggal Selesai',
        ]), $filename);
    }

    // Export Pemeliharaan Ruangan
    public function pemeliharaanRuangan(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', PemeliharaanRuangan::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = PemeliharaanRuangan::with(['ruangan']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $pemeliharaan = $query->get();

        $data = $pemeliharaan->map(function ($item) {
            return [
                'ID Pemeliharaan' => $item->id,
                'Ruangan' => $item->ruangan->nama,
                'Judul' => $item->judul,
                'Deskripsi' => $item->deskripsi,
                'Tanggal Dibuat' => $item->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $filename = 'pemeliharaan_ruangan_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID Pemeliharaan', 'Ruangan', 'Judul', 'Deskripsi', 'Tanggal Dibuat',
        ]), $filename);
    }

    // Export Top Peminjam
    public function topPeminjam(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = Jadwal::with(['peminjam', 'ruangan']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $query->where('status', 'DISETUJUI');

        $jadwals = $query->get();

        $peminjamStats = $jadwals->groupBy('peminjam.name')->map(function ($group) {
            $totalJam = $group->flatMap->tanggalJadwals->sum('durasi');
            $uniqueRuangans = $group->pluck('ruangan.nama')->unique()->count();

            return [
                'Nama Peminjam' => $group->first()->peminjam->name,
                'Email Peminjam' => $group->first()->peminjam->email,
                'Total Peminjaman' => $group->count(),
                'Total Jam' => $totalJam,
                'Jumlah Ruangan Unik' => $uniqueRuangans,
                'Rata-rata Jam per Peminjaman' => round($totalJam / $group->count(), 2),
            ];
        })->sortByDesc('Total Peminjaman')->values();

        $filename = 'top_peminjam_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($peminjamStats->toArray(), [
            'Nama Peminjam', 'Email Peminjam', 'Total Peminjaman', 'Total Jam',
            'Jumlah Ruangan Unik', 'Rata-rata Jam per Peminjaman',
        ]), $filename);
    }

    // Export Master Data Ruangan
    public function masterRuangan(): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Ruangan::class)) {
            abort(403, 'Unauthorized action.');
        }

        $ruangans = Ruangan::with(['pemilik', 'fasilitas', 'jadwals', 'pemeriksaanRuangans', 'insidens', 'pemeliharaanRuangans'])->get();

        $data = $ruangans->map(function ($ruangan) {
            return [
                'ID' => $ruangan->id,
                'Nama Ruangan' => $ruangan->nama,
                'Lokasi' => $ruangan->lokasi,
                'Pemilik' => $ruangan->pemilik ? $ruangan->pemilik->name : '-',
                'Jumlah Fasilitas' => $ruangan->fasilitas->count(),
                'Total Peminjaman' => $ruangan->jadwals->count(),
                'Total Pemeriksaan' => $ruangan->pemeriksaanRuangans->count(),
                'Total Insiden' => $ruangan->insidens->count(),
                'Total Pemeliharaan' => $ruangan->pemeliharaanRuangans->count(),
                'Tanggal Dibuat' => $ruangan->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $filename = 'master_ruangan_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID', 'Nama Ruangan', 'Lokasi', 'Pemilik', 'Jumlah Fasilitas',
            'Total Peminjaman', 'Total Pemeriksaan', 'Total Insiden', 'Total Pemeliharaan', 'Tanggal Dibuat',
        ]), $filename);
    }

    // Export Master Data Fasilitas
    public function masterFasilitas(): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Fasilitas::class)) {
            abort(403, 'Unauthorized action.');
        }

        $fasilitas = Fasilitas::withCount(['ruangans'])->with(['fasilitasRuangans'])->get();

        $data = $fasilitas->map(function ($item) {
            // Calculate total quantity across all rooms
            $totalJumlah = $item->fasilitasRuangans->sum('jumlah');

            return [
                'ID' => $item->id,
                'Nama Fasilitas' => $item->nama,
                'Satuan' => $item->satuan,
                'Jumlah Ruangan' => $item->ruangans_count,
                'Total Kuantitas' => $totalJumlah,
                'Tanggal Dibuat' => $item->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $filename = 'master_fasilitas_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID', 'Nama Fasilitas', 'Satuan', 'Jumlah Ruangan', 'Total Kuantitas', 'Tanggal Dibuat',
        ]), $filename);
    }

    // Export Custom Query (for admin use)
    public function customQuery(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'query' => ['required', 'string'],
            'filename' => ['required', 'string'],
        ]);

        try {
            $results = DB::select(DB::raw($request->query));

            if (empty($results)) {
                return back()->with('error', 'Query tidak menghasilkan data.');
            }

            $data = array_map(function ($row) {
                return (array) $row;
            }, $results);

            $headers = array_keys($data[0] ?? []);

            return Excel::download(new GenericExport($data, $headers), $request->filename.'.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Query gagal dieksekusi: '.$e->getMessage());
        }
    }

    // Export Lampiran Pengajuan
    public function lampiranPengajuan(Request $request): BinaryFileResponse
    {
        if (! Auth::user()->can('viewAny', LampiranJadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $query = LampiranJadwal::with(['jadwal.ruangan', 'jadwal.peminjam']);

        // Apply filters
        if ($request->filled('ruangan_id')) {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('ruangan_id', $request->ruangan_id);
            });
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereHas('jadwal.tanggalJadwals', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $lampiran = $query->get();

        $data = $lampiran->map(function ($item) {
            return [
                'ID' => $item->id,
                'Nama Jadwal' => $item->jadwal->keperluan,
                'Ruangan' => $item->jadwal->ruangan->nama,
                'Peminjam' => $item->jadwal->peminjam->name,
                'Tipe Lampiran' => $item->tipe,
                'Nama File' => $item->nama_file,
                'URL' => $item->url,
                'Deskripsi' => $item->deskripsi,
                'Tanggal Upload' => $item->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $filename = 'lampiran_pengajuan_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new GenericExport($data, [
            'ID', 'Nama Jadwal', 'Ruangan', 'Peminjam', 'Tipe Lampiran', 'Nama File',
            'URL', 'Deskripsi', 'Tanggal Upload',
        ]), $filename);
    }
}

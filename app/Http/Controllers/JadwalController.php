<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalRequest;
use App\Http\Requests\UpdateJadwalRequest;
use App\Models\Jadwal;
use App\Models\RiwayatStatusJadwal;
use App\Models\Ruangan;
use App\Models\TanggalJadwal;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(Request $request): View
    {
        $query = Jadwal::with(['peminjam', 'ruangan.pemilik', 'tanggalJadwals', 'persetujuanJadwals.aktor']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('keperluan', 'like', '%'.$request->search.'%')
                    ->orWhereHas('peminjam', function ($qq) use ($request) {
                        $qq->where('name', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('ruangan', function ($qq) use ($request) {
                        $qq->where('nama', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan peminjam
        if ($request->filled('peminjam_id')) {
            $query->where('peminjam_id', $request->peminjam_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereHas('tanggalJadwals', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            });
        }

        if (Auth::user()->role === 'USER') {
            $query->where('peminjam_id', Auth::user()->id);
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $jadwals = $query->paginate(15)->withQueryString();

        $ruangans = Ruangan::orderBy('nama')->get();
        $users = User::orderBy('name')->get();

        return view('jadwals.index', compact('jadwals', 'ruangans', 'users'));
    }

    public function calendar(Request $request): View
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'ADMIN';

        $query = Jadwal::with(['ruangan', 'tanggalJadwals', 'peminjam'])
            ->whereHas('tanggalJadwals', function ($q) {
                $q->where('tanggal', '>=', now()->toDateString());
            });

        // For non-admin users, only show their own schedules
        if (! $isAdmin) {
            $query->where('peminjam_id', $user->id);
        }

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by ruangan if specified
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter by month if specified
        if ($request->filled('month')) {
            $query->whereHas('tanggalJadwals', function ($q) use ($request) {
                $q->whereMonth('tanggal', date('m', strtotime($request->month)))
                    ->whereYear('tanggal', date('Y', strtotime($request->month)))
                    ->where('tanggal', '>=', now()->toDateString());
            });
        }

        $jadwals = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        $ruangans = \App\Models\Ruangan::orderBy('nama')->get();

        // Group jadwals by date for calendar view
        $eventsByDate = [];
        foreach ($jadwals as $jadwal) {
            foreach ($jadwal->tanggalJadwals as $tanggal) {
                $date = $tanggal->tanggal->format('Y-m-d');
                if (! isset($eventsByDate[$date])) {
                    $eventsByDate[$date] = [];
                }
                $eventsByDate[$date][] = [
                    'jadwal' => $jadwal,
                    'tanggal' => $tanggal,
                ];
            }
        }

        return view('kalender', compact('eventsByDate', 'ruangans', 'jadwals'));
    }

    public function calendarPage(Request $request): View
    {
        $query = Jadwal::with(['ruangan', 'tanggalJadwals'])
            ->where('status', 'DISETUJUI');

        // Filter by ruangan if specified
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter by month if specified
        if ($request->filled('month')) {
            $query->whereHas('tanggalJadwals', function ($q) use ($request) {
                $q->whereMonth('tanggal', date('m', strtotime($request->month)))
                    ->whereYear('tanggal', date('Y', strtotime($request->month)));
            });
        }

        $jadwals = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        $ruangans = \App\Models\Ruangan::orderBy('nama')->get();

        // Group jadwals by date for calendar view
        $eventsByDate = [];
        foreach ($jadwals as $jadwal) {
            foreach ($jadwal->tanggalJadwals as $tanggal) {
                $date = $tanggal->tanggal->format('Y-m-d');
                if (! isset($eventsByDate[$date])) {
                    $eventsByDate[$date] = [];
                }
                $eventsByDate[$date][] = [
                    'jadwal' => $jadwal,
                    'tanggal' => $tanggal,
                ];
            }
        }

        return view('calendar-page', compact('eventsByDate', 'ruangans', 'jadwals'));
    }

    public function create(): View
    {
        if (! Auth::user()->can('create', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $ruangans = Ruangan::with('fasilitas')->orderBy('nama')->get();

        return view('jadwals.create', compact('ruangans'));
    }

    public function store(StoreJadwalRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $jadwal = Jadwal::create([
                'keperluan' => $request->keperluan,
                'status' => 'MENUNGGU',
                'peminjam_id' => auth()->id(),
                'ruangan_id' => $request->ruangan_id,
            ]);

            // Simpan riwayat status awal
            RiwayatStatusJadwal::create([
                'jadwal_id' => $jadwal->id,
                'dari' => null,
                'menjadi' => 'MENUNGGU',
                'aktor_id' => auth()->id(),
            ]);

            // Simpan tanggal jadwal
            foreach ($request->tanggal_jadwals as $tanggalJadwalData) {
                TanggalJadwal::create([
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $tanggalJadwalData['tanggal'],
                    'jam_mulai' => $tanggalJadwalData['jam_mulai'] ?? null,
                    'jam_berakhir' => $tanggalJadwalData['jam_berakhir'] ?? null,
                ]);
            }

            DB::commit();

            $message = 'Pengajuan jadwal berhasil dibuat dengan '.count($request->tanggal_jadwals).' tanggal. Menunggu persetujuan.';

            return redirect()
                ->route('jadwal.show', $jadwal)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat jadwal. Silakan coba lagi.');
        }
    }

    public function show(Jadwal $jadwal): View
    {
        if (! Auth::user()->can('view', $jadwal)) {
            abort(403, 'Unauthorized action.');
        }

        $jadwal->load([
            'peminjam',
            'ruangan.pemilik',
            'ruangan.fasilitas',
            'tanggalJadwals',
            'persetujuanJadwals.aktor',
            'riwayatStatusJadwals.aktor',
            'pemeriksaanRuangans.petugas',
            'insidens.pelapor',
        ]);

        return view('jadwals.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal): View
    {
        if (! Auth::user()->can('update', $jadwal)) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya jadwal dengan status MENUNGGU yang bisa diedit
        if ($jadwal->status !== 'MENUNGGU') {
            abort(403, 'Jadwal tidak dapat diedit karena sudah diproses.');
        }

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('jadwals.edit', compact('jadwal', 'ruangans'));
    }

    public function update(UpdateJadwalRequest $request, Jadwal $jadwal): RedirectResponse
    {
        if (! Auth::user()->can('update', $jadwal)) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya jadwal dengan status MENUNGGU yang bisa diedit
        if ($jadwal->status !== 'MENUNGGU') {
            return back()->with('error', 'Jadwal tidak dapat diedit karena sudah diproses.');
        }

        DB::beginTransaction();

        try {
            // Only update jadwal basic info, skip tanggal jadwals validation
            $jadwalData = $request->only(['keperluan', 'ruangan_id']);
            $jadwal->update($jadwalData);

            // Update tanggal jadwals if provided and not empty
            if ($request->filled('tanggal_jadwals')) {
                // Get existing tanggal jadwal IDs
                $existingIds = $jadwal->tanggalJadwals->pluck('id')->toArray();
                $newIds = [];

                foreach ($request->tanggal_jadwals as $tanggalJadwalData) {
                    // Validate tanggal jadwal data manually
                    $validator = validator($tanggalJadwalData, [
                        'tanggal' => ['required', 'date', 'after_or_equal:today'],
                        'jam_mulai' => ['nullable', 'date_format:H:i'],
                        'jam_berakhir' => ['nullable', 'date_format:H:i', 'required_with:jam_mulai', 'after:jam_mulai'],
                    ]);

                    if ($validator->fails()) {
                        DB::rollBack();

                        return back()
                            ->withInput()
                            ->withErrors($validator->errors()->merge(['tanggal_jadwals' => 'Tanggal jadwal tidak valid.']));
                    }

                    if (isset($tanggalJadwalData['id'])) {
                        // Update existing tanggal jadwal
                        $tanggalJadwal = TanggalJadwal::find($tanggalJadwalData['id']);
                        if ($tanggalJadwal && $tanggalJadwal->jadwal_id == $jadwal->id) {
                            $tanggalJadwal->update([
                                'tanggal' => $tanggalJadwalData['tanggal'],
                                'jam_mulai' => $tanggalJadwalData['jam_mulai'] ?? null,
                                'jam_berakhir' => $tanggalJadwalData['jam_berakhir'] ?? null,
                            ]);
                            $newIds[] = $tanggalJadwal->id;
                        }
                    } else {
                        // Create new tanggal jadwal
                        $newTanggalJadwal = TanggalJadwal::create([
                            'jadwal_id' => $jadwal->id,
                            'tanggal' => $tanggalJadwalData['tanggal'],
                            'jam_mulai' => $tanggalJadwalData['jam_mulai'] ?? null,
                            'jam_berakhir' => $tanggalJadwalData['jam_berakhir'] ?? null,
                        ]);
                        $newIds[] = $newTanggalJadwal->id;
                    }
                }

                // Delete tanggal jadwals that were removed
                $idsToDelete = array_diff($existingIds, $newIds);
                if (! empty($idsToDelete)) {
                    TanggalJadwal::whereIn('id', $idsToDelete)->delete();
                }
            }

            DB::commit();

            $message = 'Jadwal berhasil diperbarui.';

            return redirect()
                ->route('jadwal.show', $jadwal)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jadwal. Silakan coba lagi.');
        }
    }

    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        if (! Auth::user()->can('delete', $jadwal)) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya jadwal dengan status MENUNGGU atau DITOLAK yang bisa dihapus
        if (! in_array($jadwal->status, ['MENUNGGU', 'DITOLAK'])) {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah diproses.');
        }

        DB::beginTransaction();

        try {
            // Simpan riwayat status sebelum dihapus
            RiwayatStatusJadwal::create([
                'jadwal_id' => $jadwal->id,
                'dari' => $jadwal->status,
                'menjadi' => 'DIBATALKAN',
                'aktor_id' => auth()->id(),
            ]);

            $jadwal->delete();

            DB::commit();

            return redirect()
                ->route('jadwal.index')
                ->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus jadwal. Silakan coba lagi.');
        }
    }

    public function approve(Jadwal $jadwal): RedirectResponse
    {
        if ($jadwal->status !== 'MENUNGGU') {
            return back()->with('error', 'Jadwal tidak dapat disetujui karena statusnya bukan MENUNGGU.');
        }

        DB::beginTransaction();

        try {
            // Update status jadwal
            $jadwal->update(['status' => 'DISETUJUI']);

            // Simpan persetujuan
            $jadwal->persetujuanJadwals()->create([
                'aktor_id' => auth()->id(),
                'status' => 'DISETUJUI',
                'catatan' => request('catatan'),
            ]);

            // Simpan riwayat status
            RiwayatStatusJadwal::create([
                'jadwal_id' => $jadwal->id,
                'dari' => 'MENUNGGU',
                'menjadi' => 'DISETUJUI',
                'aktor_id' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('jadwal.show', $jadwal)
                ->with('success', 'Jadwal berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyetujui jadwal. Silakan coba lagi.');
        }
    }

    public function reject(Jadwal $jadwal): RedirectResponse
    {
        if (! Auth::user()->can('reject', $jadwal)) {
            abort(403, 'Unauthorized action.');
        }

        if ($jadwal->status !== 'MENUNGGU') {
            return back()->with('error', 'Jadwal tidak dapat ditolak karena statusnya bukan MENUNGGU.');
        }

        request()->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ]);

        DB::beginTransaction();

        try {
            // Update status jadwal
            $jadwal->update(['status' => 'DITOLAK']);

            // Simpan penolakan
            $jadwal->persetujuanJadwals()->create([
                'aktor_id' => auth()->id(),
                'status' => 'DITOLAK',
                'catatan' => request('catatan'),
            ]);

            // Simpan riwayat status
            RiwayatStatusJadwal::create([
                'jadwal_id' => $jadwal->id,
                'dari' => 'MENUNGGU',
                'menjadi' => 'DITOLAK',
                'aktor_id' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('jadwal.show', $jadwal)
                ->with('success', 'Jadwal berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menolak jadwal. Silakan coba lagi.');
        }
    }
}

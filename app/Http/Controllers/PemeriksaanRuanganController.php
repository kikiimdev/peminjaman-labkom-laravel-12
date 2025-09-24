<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemeriksaanRuanganRequest;
use App\Http\Requests\UpdatePemeriksaanRuanganRequest;
use App\Models\Jadwal;
use App\Models\PemeriksaanRuangan;
use App\Models\Ruangan;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemeriksaanRuanganController extends Controller
{
    public function index(Request $request): View
    {
        $query = PemeriksaanRuangan::with(['jadwal.peminjam', 'ruangan.pemilik', 'petugas']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kondisi', 'like', '%'.$request->search.'%')
                    ->orWhereHas('jadwal', function ($qq) use ($request) {
                        $qq->where('keperluan', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('ruangan', function ($qq) use ($request) {
                        $qq->where('nama', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('petugas', function ($qq) use ($request) {
                        $qq->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan petugas
        if ($request->filled('petugas_id')) {
            $query->where('petugas_id', $request->petugas_id);
        }

        // Filter berdasarkan jadwal
        if ($request->filled('jadwal_id')) {
            $query->where('jadwal_id', $request->jadwal_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $pemeriksaanRuangans = $query->paginate(15)->withQueryString();

        return view('pemeriksaan-ruangans.index', compact('pemeriksaanRuangans'));
    }

    public function create(Request $request): View
    {
        // if (! Auth::user()->can('create', PemeriksaanRuangan::class)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $jadwalId = $request->jadwal_id;
        $ruanganId = $request->ruangan_id;
        $jadwal = null;
        $ruangan = null;

        if ($jadwalId) {
            $jadwal = Jadwal::findOrFail($jadwalId);
            Auth::user()->can('view', $jadwal);
            $ruanganId = $jadwal->ruangan_id;
        }

        if ($ruanganId) {
            $ruangan = Ruangan::findOrFail($ruanganId);
        }

        $jadwals = Jadwal::with(['peminjam', 'ruangan'])->get();
        $ruangans = Ruangan::orderBy('nama')->get();

        return view('pemeriksaan-ruangans.create', compact('jadwals', 'ruangans', 'jadwal', 'ruangan'));
    }

    public function store(StorePemeriksaanRuanganRequest $request): RedirectResponse
    {
        $pemeriksaanRuangan = PemeriksaanRuangan::create($request->validated());

        return redirect()
            ->route('jadwal.show', $pemeriksaanRuangan->jadwal_id)
            ->with('success', 'Pemeriksaan ruangan berhasil ditambahkan.');
    }

    public function show(PemeriksaanRuangan $pemeriksaan_ruangan): View
    {
        // if (! Auth::user()->can('view', $pemeriksaan_ruangan)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $pemeriksaan_ruangan->load(['jadwal.peminjam', 'ruangan.pemilik', 'petugas']);
        dd($pemeriksaan_ruangan);

        return view('pemeriksaan-ruangans.show', compact('pemeriksaan_ruangan'));
    }

    public function edit(PemeriksaanRuangan $pemeriksaan_ruangan): View
    {
        // if (! Auth::user()->can('update', $pemeriksaan_ruangan)) {
        //     abort(403, 'Unauthorized action.');
        // }

        return view('pemeriksaan-ruangans.edit', compact('pemeriksaan_ruangan'));
    }

    public function update(UpdatePemeriksaanRuanganRequest $request, PemeriksaanRuangan $pemeriksaan_ruangan): RedirectResponse
    {
        $pemeriksaan_ruangan->update($request->validated());

        return redirect()
            ->route('pemeriksaan-ruangans.show', $pemeriksaan_ruangan)
            ->with('success', 'Pemeriksaan ruangan berhasil diperbarui.');
    }

    public function destroy(PemeriksaanRuangan $pemeriksaan_ruangan): RedirectResponse
    {
        // if (! Auth::user()->can('delete', $pemeriksaan_ruangan)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $jadwalId = $pemeriksaan_ruangan->jadwal_id;
        $pemeriksaan_ruangan->delete();

        return redirect()
            ->route('jadwal.show', $jadwalId)
            ->with('success', 'Pemeriksaan ruangan berhasil dihapus.');
    }

    public function checkIn(Jadwal $jadwal): View
    {
        // if (! Auth::user()->can('checkIn', $jadwal)) {
        //     abort(403, 'Unauthorized action.');
        // }

        if ($jadwal->status !== 'DISETUJUI') {
            abort(403, 'Hanya jadwal yang disetujui yang dapat melakukan check-in.');
        }

        // Cek apakah sudah ada pemeriksaan check-in
        $existingCheckIn = $jadwal->pemeriksaanRuangans()
            ->where('kondisi', 'CHECK_IN')
            ->exists();

        if ($existingCheckIn) {
            return back()->with('error', 'Check-in sudah dilakukan untuk jadwal ini.');
        }

        return view('pemeriksaan-ruangans.check-in', compact('jadwal'));
    }

    public function storeCheckIn(Request $request, Jadwal $jadwal): RedirectResponse
    {
        // if (! Auth::user()->can('checkIn', $jadwal)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $request->validate([
            'kondisi' => ['required', 'string', 'in:BAIK,BUTUH_PERBAIKAN,RUSAK'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        // Cek apakah sudah ada pemeriksaan check-in
        $existingCheckIn = $jadwal->pemeriksaanRuangans()
            ->where('kondisi', 'CHECK_IN')
            ->exists();

        if ($existingCheckIn) {
            return back()->with('error', 'Check-in sudah dilakukan untuk jadwal ini.');
        }

        $pemeriksaan = PemeriksaanRuangan::create([
            'jadwal_id' => $jadwal->id,
            'ruangan_id' => $jadwal->ruangan_id,
            'petugas_id' => auth()->id(),
            'kondisi' => $request->kondisi,
        ]);

        return redirect()
            ->route('jadwal.show', $jadwal)
            ->with('success', 'Check-in berhasil dilakukan.');
    }

    public function checkOut(Jadwal $jadwal): View
    {
        // if (! Auth::user()->can('checkOut', $jadwal)) {
        //     abort(403, 'Unauthorized action.');
        // }

        if ($jadwal->status !== 'DISETUJUI') {
            abort(403, 'Hanya jadwal yang disetujui yang dapat melakukan check-out.');
        }

        // Cek apakah sudah ada pemeriksaan check-in
        $checkIn = $jadwal->pemeriksaanRuangans()
            ->where('kondisi', 'CHECK_IN')
            ->first();

        if (! $checkIn) {
            return back()->with('error', 'Harap lakukan check-in terlebih dahulu.');
        }

        // Cek apakah sudah ada pemeriksaan check-out
        $existingCheckOut = $jadwal->pemeriksaanRuangans()
            ->where('kondisi', 'CHECK_OUT')
            ->exists();

        if ($existingCheckOut) {
            return back()->with('error', 'Check-out sudah dilakukan untuk jadwal ini.');
        }

        return view('pemeriksaan-ruangans.check-out', compact('jadwal', 'checkIn'));
    }

    public function storeCheckOut(Request $request, Jadwal $jadwal): RedirectResponse
    {
        // if (! Auth::user()->can('checkOut', $jadwal)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $request->validate([
            'kondisi' => ['required', 'string', 'in:BAIK,BUTUH_PERBAIKAN,RUSAK'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        // Cek apakah sudah ada pemeriksaan check-out
        $existingCheckOut = $jadwal->pemeriksaanRuangans()
            ->where('kondisi', 'CHECK_OUT')
            ->exists();

        if ($existingCheckOut) {
            return back()->with('error', 'Check-out sudah dilakukan untuk jadwal ini.');
        }

        $pemeriksaan = PemeriksaanRuangan::create([
            'jadwal_id' => $jadwal->id,
            'ruangan_id' => $jadwal->ruangan_id,
            'petugas_id' => auth()->id(),
            'kondisi' => $request->kondisi,
        ]);

        return redirect()
            ->route('jadwal.show', $jadwal)
            ->with('success', 'Check-out berhasil dilakukan.');
    }

    public function byRuangan(Ruangan $ruangan): View
    {
        // if (! Auth::user()->can('view', $ruangan)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $pemeriksaan = $ruangan->pemeriksaanRuangans()
            ->with(['jadwal.peminjam', 'petugas'])
            ->latestFirst()
            ->paginate(20);

        return view('pemeriksaan-ruangans.by-ruangan', compact('pemeriksaan', 'ruangan'));
    }

    public function byJadwal(Jadwal $jadwal): View
    {
        // if (! Auth::user()->can('view', $jadwal)) {
        //     abort(403, 'Unauthorized action.');
        // }

        $pemeriksaan = $jadwal->pemeriksaanRuangans()
            ->with('petugas')
            ->latestFirst()
            ->get();

        return view('pemeriksaan-ruangans.by-jadwal', compact('pemeriksaan', 'jadwal'));
    }
}

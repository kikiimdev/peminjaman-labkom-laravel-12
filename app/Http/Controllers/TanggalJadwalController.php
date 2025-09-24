<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTanggalJadwalRequest;
use App\Http\Requests\UpdateTanggalJadwalRequest;
use App\Models\Jadwal;
use App\Models\TanggalJadwal;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TanggalJadwalController extends Controller
{
    public function index(Request $request): View
    {
        $query = TanggalJadwal::with(['jadwal.peminjam', 'jadwal.ruangan']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('jadwal', function ($qq) use ($request) {
                    $qq->where('keperluan', 'like', '%'.$request->search.'%')
                        ->orWhereHas('peminjam', function ($qqq) use ($request) {
                            $qqq->where('name', 'like', '%'.$request->search.'%');
                        })
                        ->orWhereHas('ruangan', function ($qqq) use ($request) {
                            $qqq->where('nama', 'like', '%'.$request->search.'%');
                        });
                });
            });
        }

        // Filter berdasarkan jadwal
        if ($request->filled('jadwal_id')) {
            $query->where('jadwal_id', $request->jadwal_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'tanggal');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $tanggalJadwals = $query->paginate(20)->withQueryString();

        return view('tanggal-jadwals.index', compact('tanggalJadwals'));
    }

    public function create(Request $request): View
    {
        if (! Auth::user()->can('create', TanggalJadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $jadwalId = $request->jadwal_id;
        $jadwal = null;

        if ($jadwalId) {
            $jadwal = Jadwal::findOrFail($jadwalId);
            // $this->authorize('view', $jadwal);
            Auth::user()->can('view', $jadwal);
        }

        $jadwals = Jadwal::where('status', 'MENUNGGU')
            ->with(['ruangan', 'peminjam'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tanggal-jadwals.create', compact('jadwals', 'jadwal'));
    }

    public function store(StoreTanggalJadwalRequest $request): RedirectResponse
    {
        $tanggalJadwal = TanggalJadwal::create($request->validated());

        return redirect()
            ->route('jadwals.show', $tanggalJadwal->jadwal_id)
            ->with('success', 'Tanggal jadwal berhasil ditambahkan.');
    }

    public function show(TanggalJadwal $tanggal_jadwal): View
    {
        $this->authorize('view', $tanggal_jadwal);

        $tanggal_jadwal->load(['jadwal.peminjam', 'jadwal.ruangan']);

        return view('tanggal-jadwals.show', compact('tanggal_jadwal'));
    }

    public function edit(TanggalJadwal $tanggal_jadwal): View
    {
        $this->authorize('update', $tanggal_jadwal);

        // Hanya jadwal dengan status MENUNGGU yang bisa diedit
        if ($tanggal_jadwal->jadwal->status !== 'MENUNGGU') {
            abort(403, 'Tanggal jadwal tidak dapat diedit karena jadwal sudah diproses.');
        }

        return view('tanggal-jadwals.edit', compact('tanggal_jadwal'));
    }

    public function update(UpdateTanggalJadwalRequest $request, TanggalJadwal $tanggal_jadwal): RedirectResponse
    {
        $this->authorize('update', $tanggal_jadwal);

        $tanggal_jadwal->update($request->validated());

        return redirect()
            ->route('jadwals.show', $tanggal_jadwal->jadwal_id)
            ->with('success', 'Tanggal jadwal berhasil diperbarui.');
    }

    public function destroy(TanggalJadwal $tanggal_jadwal): RedirectResponse
    {
        $this->authorize('delete', $tanggal_jadwal);

        // Hanya jadwal dengan status MENUNGGU yang bisa dihapus
        if ($tanggal_jadwal->jadwal->status !== 'MENUNGGU') {
            return back()->with('error', 'Tanggal jadwal tidak dapat dihapus karena jadwal sudah diproses.');
        }

        $jadwalId = $tanggal_jadwal->jadwal_id;
        $tanggal_jadwal->delete();

        return redirect()
            ->route('jadwals.show', $jadwalId)
            ->with('success', 'Tanggal jadwal berhasil dihapus.');
    }

    public function calendar(Request $request): View
    {
        $query = TanggalJadwal::with(['jadwal.peminjam', 'jadwal.ruangan'])
            ->whereHas('jadwal', function ($q) {
                $q->whereIn('status', ['DISETUJUI']);
            });

        if ($request->filled('ruangan_id')) {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('ruangan_id', $request->ruangan_id);
            });
        }

        $tanggalJadwals = $query->get();

        return view('tanggal-jadwals.calendar', compact('tanggalJadwals'));
    }
}

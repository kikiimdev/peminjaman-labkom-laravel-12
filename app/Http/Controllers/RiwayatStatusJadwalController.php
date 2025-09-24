<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatStatusJadwalRequest;
use App\Http\Requests\UpdateRiwayatStatusJadwalRequest;
use App\Models\Jadwal;
use App\Models\RiwayatStatusJadwal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatStatusJadwalController extends Controller
{
    public function index(Request $request): View
    {
        $query = RiwayatStatusJadwal::with(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('dari', 'like', '%'.$request->search.'%')
                    ->orWhere('menjadi', 'like', '%'.$request->search.'%')
                    ->orWhereHas('jadwal', function ($qq) use ($request) {
                        $qq->where('keperluan', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('aktor', function ($qq) use ($request) {
                        $qq->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter berdasarkan status perubahan
        if ($request->filled('dari')) {
            $query->where('dari', $request->dari);
        }

        if ($request->filled('menjadi')) {
            $query->where('menjadi', $request->menjadi);
        }

        // Filter berdasarkan aktor
        if ($request->filled('aktor_id')) {
            $query->where('aktor_id', $request->aktor_id);
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

        $riwayatStatusJadwals = $query->paginate(20)->withQueryString();

        return view('riwayat-status-jadwals.index', compact('riwayatStatusJadwals'));
    }

    public function create(): View
    {
        $this->authorize('create', RiwayatStatusJadwal::class);

        $jadwals = Jadwal::with(['peminjam', 'ruangan'])->get();

        return view('riwayat-status-jadwals.create', compact('jadwals'));
    }

    public function store(StoreRiwayatStatusJadwalRequest $request): RedirectResponse
    {
        $riwayatStatusJadwal = RiwayatStatusJadwal::create($request->validated());

        return redirect()
            ->route('jadwals.show', $riwayatStatusJadwal->jadwal_id)
            ->with('success', 'Riwayat status jadwal berhasil ditambahkan.');
    }

    public function show(RiwayatStatusJadwal $riwayat_status_jadwal): View
    {
        $this->authorize('view', $riwayat_status_jadwal);

        $riwayat_status_jadwal->load(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        return view('riwayat-status-jadwals.show', compact('riwayat_status_jadwal'));
    }

    public function edit(RiwayatStatusJadwal $riwayat_status_jadwal): View
    {
        $this->authorize('update', $riwayat_status_jadwal);

        return view('riwayat-status-jadwals.edit', compact('riwayat_status_jadwal'));
    }

    public function update(UpdateRiwayatStatusJadwalRequest $request, RiwayatStatusJadwal $riwayat_status_jadwal): RedirectResponse
    {
        // Riwayat status tidak dapat diubah
        return back()->with('error', 'Riwayat status tidak dapat diubah.');
    }

    public function destroy(RiwayatStatusJadwal $riwayat_status_jadwal): RedirectResponse
    {
        $this->authorize('delete', $riwayat_status_jadwal);

        $jadwalId = $riwayat_status_jadwal->jadwal_id;
        $riwayat_status_jadwal->delete();

        return redirect()
            ->route('jadwals.show', $jadwalId)
            ->with('success', 'Riwayat status jadwal berhasil dihapus.');
    }

    public function byJadwal(Jadwal $jadwal): View
    {
        $this->authorize('view', $jadwal);

        $riwayatStatus = $jadwal->riwayatStatusJadwals()
            ->with('aktor')
            ->latestFirst()
            ->get();

        return view('riwayat-status-jadwals.by-jadwal', compact('riwayatStatus', 'jadwal'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersetujuanJadwalRequest;
use App\Http\Requests\UpdatePersetujuanJadwalRequest;
use App\Models\Jadwal;
use App\Models\PersetujuanJadwal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersetujuanJadwalController extends Controller
{
    public function index(Request $request): View
    {
        $query = PersetujuanJadwal::with(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('catatan', 'like', '%'.$request->search.'%')
                    ->orWhereHas('jadwal', function ($qq) use ($request) {
                        $qq->where('keperluan', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('aktor', function ($qq) use ($request) {
                        $qq->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan aktor
        if ($request->filled('aktor_id')) {
            $query->where('aktor_id', $request->aktor_id);
        }

        // Filter berdasarkan jadwal
        if ($request->filled('jadwal_id')) {
            $query->where('jadwal_id', $request->jadwal_id);
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $persetujuanJadwals = $query->paginate(15)->withQueryString();

        return view('persetujuan-jadwals.index', compact('persetujuanJadwals'));
    }

    public function create(): View
    {
        $this->authorize('create', PersetujuanJadwal::class);

        $jadwals = Jadwal::where('status', 'MENUNGGU')
            ->with(['peminjam', 'ruangan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('persetujuan-jadwals.create', compact('jadwals'));
    }

    public function store(StorePersetujuanJadwalRequest $request): RedirectResponse
    {
        $persetujuanJadwal = PersetujuanJadwal::create($request->validated());

        // Update status jadwal
        $jadwal = $persetujuanJadwal->jadwal;
        $jadwal->update(['status' => $persetujuanJadwal->status]);

        return redirect()
            ->route('jadwals.show', $persetujuanJadwal->jadwal_id)
            ->with('success', 'Persetujuan jadwal berhasil diproses.');
    }

    public function show(PersetujuanJadwal $persetujuan_jadwal): View
    {
        $this->authorize('view', $persetujuan_jadwal);

        $persetujuan_jadwal->load(['jadwal.peminjam', 'jadwal.ruangan', 'aktor']);

        return view('persetujuan-jadwals.show', compact('persetujuan_jadwal'));
    }

    public function edit(PersetujuanJadwal $persetujuan_jadwal): View
    {
        $this->authorize('update', $persetujuan_jadwal);

        return view('persetujuan-jadwals.edit', compact('persetujuan_jadwal'));
    }

    public function update(UpdatePersetujuanJadwalRequest $request, PersetujuanJadwal $persetujuan_jadwal): RedirectResponse
    {
        $oldStatus = $persetujuan_jadwal->status;
        $persetujuan_jadwal->update($request->validated());

        // Update status jadwal jika status berubah
        if ($oldStatus !== $persetujuan_jadwal->status) {
            $persetujuan_jadwal->jadwal->update(['status' => $persetujuan_jadwal->status]);
        }

        return redirect()
            ->route('persetujuan-jadwals.show', $persetujuan_jadwal)
            ->with('success', 'Persetujuan jadwal berhasil diperbarui.');
    }

    public function destroy(PersetujuanJadwal $persetujuan_jadwal): RedirectResponse
    {
        $this->authorize('delete', $persetujuan_jadwal);

        $jadwalId = $persetujuan_jadwal->jadwal_id;
        $persetujuan_jadwal->delete();

        return redirect()
            ->route('jadwals.show', $jadwalId)
            ->with('success', 'Persetujuan jadwal berhasil dihapus.');
    }
}

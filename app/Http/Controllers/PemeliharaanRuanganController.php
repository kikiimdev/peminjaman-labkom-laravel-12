<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemeliharaanRuanganRequest;
use App\Http\Requests\UpdatePemeliharaanRuanganRequest;
use App\Models\PemeliharaanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemeliharaanRuanganController extends Controller
{
    public function index(Request $request): View
    {
        $query = PemeliharaanRuangan::with(['ruangan.pemilik']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$request->search.'%')
                    ->orWhereHas('ruangan', function ($qq) use ($request) {
                        $qq->where('nama', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Urutkan berdasarkan created_at
        $query->latest('created_at');

        $pemeliharaanRuangans = $query->paginate(15)->withQueryString();

        return view('pemeliharaan-ruangans.index', compact('pemeliharaanRuangans'));
    }

    public function create(Request $request): View
    {
        // $this->authorize('create', PemeliharaanRuangan::class);

        $ruanganId = $request->ruangan_id;
        $ruangan = null;

        if ($ruanganId) {
            $ruangan = Ruangan::findOrFail($ruanganId);
            // $this->authorize('view', $ruangan);
        }

        $ruangans = Ruangan::orderBy('nama')->get();

        return view('pemeliharaan-ruangans.create', compact('ruangans', 'ruangan'));
    }

    public function store(StorePemeliharaanRuanganRequest $request): RedirectResponse
    {
        $pemeliharaanRuangan = PemeliharaanRuangan::create($request->validated());

        return redirect()
            ->route('pemeliharaan.show', $pemeliharaanRuangan)
            ->with('success', 'Pemeliharaan ruangan berhasil dijadwalkan.');
    }

    public function show(PemeliharaanRuangan $pemeliharaan): View
    {
        // $this->authorize('view', $pemeliharaan);

        $pemeliharaan->load(['ruangan.pemilik']);

        return view('pemeliharaan-ruangans.show', compact('pemeliharaan'));
    }

    public function edit(PemeliharaanRuangan $pemeliharaan): View
    {
        // $this->authorize('update', $pemeliharaan);

        return view('pemeliharaan-ruangans.edit', compact('pemeliharaan'));
    }

    public function update(UpdatePemeliharaanRuanganRequest $request, PemeliharaanRuangan $pemeliharaan): RedirectResponse
    {
        // $this->authorize('update', $pemeliharaan);

        $pemeliharaan->update($request->validated());

        return redirect()
            ->route('pemeliharaan.show', $pemeliharaan)
            ->with('success', 'Pemeliharaan ruangan berhasil diperbarui.');
    }

    public function destroy(PemeliharaanRuangan $pemeliharaan): RedirectResponse
    {
        // $this->authorize('delete', $pemeliharaan);

        $pemeliharaan->delete();

        return redirect()
            ->route('pemeliharaan.index')
            ->with('success', 'Pemeliharaan ruangan berhasil dihapus.');
    }

    public function byRuangan(Ruangan $ruangan): View
    {
        // $this->authorize('view', $ruangan);

        $pemeliharaan = $ruangan->pemeliharaanRuangans()
            ->latestFirst()
            ->paginate(20);

        return view('pemeliharaan-ruangans.by-ruangan', compact('pemeliharaan', 'ruangan'));
    }

    public function calendar(Request $request): View
    {
        // $this->authorize('viewAny', PemeliharaanRuangan::class);

        $query = PemeliharaanRuangan::with(['ruangan']);

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $pemeliharaanRuangans = $query->latest('created_at')->get();

        return view('pemeliharaan-ruangans.calendar', compact('pemeliharaanRuangans'));
    }

    public function dashboard(Request $request): View
    {
        // $this->authorize('viewAny', PemeliharaanRuangan::class);

        $stats = [
            'total' => PemeliharaanRuangan::count(),
        ];

        $recentMaintenance = PemeliharaanRuangan::with('ruangan')
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('pemeliharaan-ruangans.dashboard', compact(
            'stats',
            'recentMaintenance'
        ));
    }
}

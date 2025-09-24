<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInsidenRequest;
use App\Http\Requests\UpdateInsidenRequest;
use App\Models\Insiden;
use App\Models\Jadwal;
use App\Models\Ruangan;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InsidenController extends Controller
{
    public function index(Request $request): View
    {
        $query = Insiden::with(['jadwal.peminjam', 'ruangan.pemilik', 'pelapor']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('tingkat', 'like', '%'.$request->search.'%')
                    ->orWhereHas('jadwal', function ($qq) use ($request) {
                        $qq->where('keperluan', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('ruangan', function ($qq) use ($request) {
                        $qq->where('nama', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('pelapor', function ($qq) use ($request) {
                        $qq->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter berdasarkan tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        // Filter berdasarkan status (open/closed)
        if ($request->filled('status')) {
            if ($request->status === 'open') {
                $query->open();
            } elseif ($request->status === 'closed') {
                $query->closed();
            }
        }

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan pelapor
        if ($request->filled('pelapor_id')) {
            $query->where('pelapor_id', $request->pelapor_id);
        }

        // Filter berdasarkan penanggung jawab
        if ($request->filled('penanggung_jawab')) {
            $query->where('ditangani_oleh', 'like', '%'.$request->penanggung_jawab.'%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $insidens = $query->paginate(15)->withQueryString();

        return view('insidens.index', compact('insidens'));
    }

    public function create(Request $request): View
    {
        if (! Auth::user()->can('create', Insiden::class)) {
            abort(403, 'Unauthorized action.');
        }

        $jadwalId = $request->jadwal_id;
        $ruanganId = $request->ruangan_id;
        $jadwal = null;
        $ruangan = null;

        if ($jadwalId) {
            $jadwal = Jadwal::findOrFail($jadwalId);
            if (! Auth::user()->can('view', $jadwal)) {
                abort(403, 'Unauthorized action.');
            }
            $ruanganId = $jadwal->ruangan_id;
        }

        if ($ruanganId) {
            $ruangan = Ruangan::findOrFail($ruanganId);
        }

        $jadwals = Jadwal::with(['peminjam', 'ruangan'])->get();
        $ruangans = Ruangan::orderBy('nama')->get();

        return view('insidens.create', compact('jadwals', 'ruangans', 'jadwal', 'ruangan'));
    }

    public function store(StoreInsidenRequest $request): RedirectResponse
    {
        $insiden = Insiden::create($request->validated());

        return redirect()
            ->route('jadwals.show', $insiden->jadwal_id)
            ->with('success', 'Insiden berhasil dilaporkan.');
    }

    public function show(Insiden $insiden): View
    {
        if (! Auth::user()->can('view', $insiden)) {
            abort(403, 'Unauthorized action.');
        }

        $insiden->load(['jadwal.peminjam', 'ruangan.pemilik', 'pelapor']);

        return view('insidens.show', compact('insiden'));
    }

    public function edit(Insiden $insiden): View
    {
        if (! Auth::user()->can('update', $insiden)) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya insiden yang masih open yang bisa diedit
        if ($insiden->isClosed()) {
            abort(403, 'Insiden sudah selesai tidak dapat diedit.');
        }

        return view('insidens.edit', compact('insiden'));
    }

    public function update(UpdateInsidenRequest $request, Insiden $insiden): RedirectResponse
    {
        if (! Auth::user()->can('update', $insiden)) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya insiden yang masih open yang bisa diedit
        if ($insiden->isClosed()) {
            return back()->with('error', 'Insiden sudah selesai tidak dapat diedit.');
        }

        $insiden->update($request->validated());

        return redirect()
            ->route('insiden.show', $insiden)
            ->with('success', 'Insiden berhasil diperbarui.');
    }

    public function destroy(Insiden $insiden): RedirectResponse
    {
        if (! Auth::user()->can('delete', $insiden)) {
            abort(403, 'Unauthorized action.');
        }

        $jadwalId = $insiden->jadwal_id;
        $insiden->delete();

        return redirect()
            ->route('jadwals.show', $jadwalId)
            ->with('success', 'Insiden berhasil dihapus.');
    }

    public function markAsCompleted(Request $request, Insiden $insiden): RedirectResponse
    {
        if (! Auth::user()->can('update', $insiden)) {
            abort(403, 'Unauthorized action.');
        }

        if ($insiden->isClosed()) {
            return back()->with('error', 'Insiden sudah selesai.');
        }

        $request->validate([
            'ditangani_oleh' => ['required', 'string', 'max:255'],
        ]);

        $insiden->markAsCompleted($request->ditangani_oleh);

        return redirect()
            ->route('insiden.show', $insiden)
            ->with('success', 'Insiden berhasil ditandai sebagai selesai.');
    }

    // assign method removed - ditangani_oleh is now a text field

    public function byRuangan(Ruangan $ruangan): View
    {
        if (! Auth::user()->can('view', $ruangan)) {
            abort(403, 'Unauthorized action.');
        }

        $insidens = $ruangan->insidens()
            ->with(['jadwal.peminjam', 'pelapor'])
            ->latestFirst()
            ->paginate(20);

        return view('insidens.by-ruangan', compact('insidens', 'ruangan'));
    }

    public function byJadwal(Jadwal $jadwal): View
    {
        if (! Auth::user()->can('view', $jadwal)) {
            abort(403, 'Unauthorized action.');
        }

        $insidens = $jadwal->insidens()
            ->with(['ruangan', 'pelapor'])
            ->latestFirst()
            ->get();

        return view('insidens.by-jadwal', compact('insidens', 'jadwal'));
    }

    public function dashboard(Request $request): View
    {
        if (! Auth::user()->can('viewAny', Insiden::class)) {
            abort(403, 'Unauthorized action.');
        }

        $stats = [
            'total' => Insiden::count(),
            'open' => Insiden::open()->count(),
            'closed' => Insiden::closed()->count(),
            'high_priority' => Insiden::where('tingkat', 'TINGGI')->open()->count(),
            'medium_priority' => Insiden::where('tingkat', 'SEDANG')->open()->count(),
            'low_priority' => Insiden::where('tingkat', 'RENDAH')->open()->count(),
        ];

        $recentInsidens = Insiden::with(['ruangan', 'pelapor'])
            ->latestFirst()
            ->take(10)
            ->get();

        $insidensByTingkat = Insiden::select('tingkat', DB::raw('count(*) as total'))
            ->groupBy('tingkat')
            ->get();

        return view('insidens.dashboard', compact('stats', 'recentInsidens', 'insidensByTingkat'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRuanganRequest;
use App\Http\Requests\UpdateRuanganRequest;
use App\Models\Ruangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RuanganController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ruangan::with(['pemilik', 'fasilitas', 'jadwals' => function ($query) {
            $query->latest()->take(5);
        }]);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                    ->orWhere('lokasi', 'like', '%'.$request->search.'%');
            });
        }

        // Filter berdasarkan pemilik
        if ($request->filled('pemilik_id')) {
            $query->where('pemilik_id', $request->pemilik_id);
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $ruangans = $query->paginate(10)->withQueryString();

        return view('ruangans.index', compact('ruangans'));
    }

    public function create(): View
    {
        if (! Auth::user()->can('create', Ruangan::class)) {
            abort(403, 'Unauthorized action.');
        }

        return view('ruangans.create');
    }

    public function store(StoreRuanganRequest $request): RedirectResponse
    {
        $ruangan = Ruangan::create($request->validated());

        return redirect()
            ->route('ruangan.show', $ruangan)
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Ruangan $ruangan): View
    {
        if (! Auth::user()->can('view', $ruangan)) {
            abort(403, 'Unauthorized action.');
        }

        $ruangan->load(['pemilik', 'fasilitas', 'jadwals' => function ($query) {
            $query->with('peminjam')->latest()->take(10);
        }]);

        return view('ruangans.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan): View
    {
        if (! Auth::user()->can('update', $ruangan)) {
            abort(403, 'Unauthorized action.');
        }

        return view('ruangans.edit', compact('ruangan'));
    }

    public function update(UpdateRuanganRequest $request, Ruangan $ruangan): RedirectResponse
    {
        $ruangan->update($request->validated());

        return redirect()
            ->route('ruangan.show', $ruangan)
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan): RedirectResponse
    {
        if (! Auth::user()->can('delete', $ruangan)) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah ruangan memiliki jadwal aktif
        if ($ruangan->jadwals()->whereIn('status', ['MENUNGGU', 'DISETUJUI'])->exists()) {
            return back()->with('error', 'Ruangan tidak dapat dihapus karena memiliki jadwal aktif.');
        }

        $ruangan->delete();

        return redirect()
            ->route('ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}

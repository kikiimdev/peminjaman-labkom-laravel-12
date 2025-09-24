<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFasilitasRuanganRequest;
use App\Http\Requests\UpdateFasilitasRuanganRequest;
use App\Models\Fasilitas;
use App\Models\FasilitasRuangan;
use App\Models\Ruangan;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FasilitasRuanganController extends Controller
{
    public function index(Request $request): View
    {
        $query = FasilitasRuangan::with(['ruangan', 'fasilitas']);

        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan fasilitas
        if ($request->filled('fasilitas_id')) {
            $query->where('fasilitas_id', $request->fasilitas_id);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->whereHas('ruangan', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%');
            })->orWhereHas('fasilitas', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%');
            });
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $fasilitasRuangans = $query->paginate(15)->withQueryString();

        $ruangans = Ruangan::orderBy('nama')->get();
        $fasilitasList = Fasilitas::orderBy('nama')->get();

        return view('fasilitas-ruangans.index', compact('fasilitasRuangans', 'ruangans', 'fasilitasList'));
    }

    public function create(Request $request): View
    {
        // Admin-only access
        if (Auth::user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $ruangans = Ruangan::orderBy('nama')->get();
        $fasilitas = Fasilitas::orderBy('nama')->get();
        $selectedRuanganId = $request->input('ruangan_id');

        return view('fasilitas-ruangans.create', compact('ruangans', 'fasilitas', 'selectedRuanganId'));
    }

    public function store(StoreFasilitasRuanganRequest $request): RedirectResponse
    {
        // Admin-only access
        if (Auth::user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah kombinasi ruangan dan fasilitas sudah ada
        $exists = FasilitasRuangan::where('ruangan_id', $request->ruangan_id)
            ->where('fasilitas_id', $request->fasilitas_id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Fasilitas ini sudah ada di ruangan tersebut.');
        }

        $fasilitasRuangan = FasilitasRuangan::create($request->validated());

        return redirect()
            ->route('fasilitas-ruangans.show', $fasilitasRuangan)
            ->with('success', 'Fasilitas ruangan berhasil ditambahkan.');
    }

    public function show(FasilitasRuangan $fasilitas_ruangan): View
    {
        // Admin-only access
        if (Auth::user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $fasilitas_ruangan->load(['ruangan', 'fasilitas']);

        // Load other facilities in the same room
        $otherFacilities = \App\Models\FasilitasRuangan::with('fasilitas')
            ->where('ruangan_id', $fasilitas_ruangan->ruangan_id)
            ->where('id', '!=', $fasilitas_ruangan->id)
            ->get();

        return view('fasilitas-ruangans.show', compact('fasilitas_ruangan', 'otherFacilities'));
    }

    public function edit(FasilitasRuangan $fasilitas_ruangan): View
    {
        // Admin-only access
        if (Auth::user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        return view('fasilitas-ruangans.edit', compact('fasilitas_ruangan'));
    }

    public function update(UpdateFasilitasRuanganRequest $request, FasilitasRuangan $fasilitas_ruangan): RedirectResponse
    {
        $fasilitas_ruangan->update($request->validated());

        return redirect()
            ->route('fasilitas-ruangans.show', $fasilitas_ruangan)
            ->with('success', 'Fasilitas ruangan berhasil diperbarui.');
    }

    public function destroy(FasilitasRuangan $fasilitas_ruangan): RedirectResponse
    {
        // Admin-only access
        if (Auth::user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $fasilitas_ruangan->delete();

        return redirect()
            ->route('fasilitas-ruangans.index')
            ->with('success', 'Fasilitas ruangan berhasil dihapus.');
    }
}

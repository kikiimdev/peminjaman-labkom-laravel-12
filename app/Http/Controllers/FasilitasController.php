<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFasilitasRequest;
use App\Http\Requests\UpdateFasilitasRequest;
use App\Models\Fasilitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(Request $request): View
    {
        $query = Fasilitas::with(['fasilitasRuangans', 'ruangans' => function ($query) {
            $query->take(5);
        }]);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                    ->orWhere('satuan', 'like', '%'.$request->search.'%');
            });
        }

        // Urutkan berdasarkan kolom yang dipilih
        $sortField = $request->get('sort', 'nama');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $fasilitas = $query->paginate(15)->withQueryString();

        return view('fasilitas.index', compact('fasilitas'));
    }

    public function create(): View
    {
        if (! Auth::user()->can('create', Fasilitas::class)) {
            abort(403, 'Unauthorized action.');
        }

        return view('fasilitas.create');
    }

    public function store(StoreFasilitasRequest $request): RedirectResponse
    {
        $fasilitas = Fasilitas::create($request->validated());

        return redirect()
            ->route('fasilitas.show', $fasilitas)
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(Fasilitas $fasilitas): View
    {
        if (! Auth::user()->can('view', $fasilitas)) {
            abort(403, 'Unauthorized action.');
        }

        $fasilitas->load(['fasilitasRuangans.ruangan', 'ruangans']);

        return view('fasilitas.show', compact('fasilitas'));
    }

    public function edit(Fasilitas $fasilitas): View
    {
        if (! Auth::user()->can('update', $fasilitas)) {
            abort(403, 'Unauthorized action.');
        }

        return view('fasilitas.edit', compact('fasilitas'));
    }

    public function update(UpdateFasilitasRequest $request, Fasilitas $fasilitas): RedirectResponse
    {
        $fasilitas->update($request->validated());

        return redirect()
            ->route('fasilitas.show', $fasilitas)
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        if (! Auth::user()->can('delete', $fasilitas)) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah fasilitas masih digunakan di ruangan
        if ($fasilitas->fasilitasRuangans()->exists()) {
            return back()->with('error', 'Fasilitas tidak dapat dihapus karena masih digunakan di beberapa ruangan.');
        }

        $fasilitas->delete();

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}

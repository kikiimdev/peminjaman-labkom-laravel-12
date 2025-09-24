<x-layouts.app :title="__('Fasilitas')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Fasilitas</h1>
            @can('create', App\Models\Fasilitas::class)
                <a href="{{ route('fasilitas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Fasilitas
                </a>
            @endcan
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('fasilitas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau satuan..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Menampilkan {{ $fasilitas->firstItem() }}-{{ $fasilitas->lastItem() }} dari {{ $fasilitas->total() }} fasilitas</p>
            @if (request()->anyFilled(['search', 'sort']))
                <a href="{{ route('fasilitas.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Fasilitas Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($fasilitas as $fasilitas_item)
                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $fasilitas_item->nama }}</h3>
                                <p class="text-sm text-gray-600">{{ $fasilitas_item->satuan }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-cube w-4 mr-2"></i>
                                <span>{{ $fasilitas_item->fasilitasRuangans->count() }} ruangan</span>
                            </div>
                        </div>

                        @if ($fasilitas_item->ruangans->count() > 0)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Digunakan di:</p>
                                <div class="space-y-1">
                                    @foreach ($fasilitas_item->ruangans->take(3) as $ruangan)
                                        <div class="text-xs text-gray-600 bg-gray-50 rounded px-2 py-1">
                                            {{ $ruangan->nama }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex space-x-2">
                            <a href="{{ route('fasilitas.show', $fasilitas_item) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm font-medium text-center">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            @can('update', $fasilitas_item)
                                <a href="{{ route('fasilitas.edit', $fasilitas_item) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium text-center">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $fasilitas->firstItem() }}-{{ $fasilitas->lastItem() }} dari {{ $fasilitas->total() }} fasilitas
            </div>
            {{ $fasilitas->links() }}
        </div>

        <!-- Empty State -->
        @if ($fasilitas->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-tools text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada fasilitas ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau tambahkan fasilitas baru.</p>
                @can('create', App\Models\Fasilitas::class)
                    <a href="{{ route('fasilitas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Tambah Fasilitas
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>
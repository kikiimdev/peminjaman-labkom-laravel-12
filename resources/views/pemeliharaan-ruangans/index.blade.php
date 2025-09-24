<x-layouts.app :title="__('Pemeliharaan Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Pemeliharaan Ruangan</h1>
            @can('create', App\Models\PemeliharaanRuangan::class)
                <a href="{{ route('pemeliharaan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Jadwalkan Pemeliharaan
                </a>
            @endcan
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('pemeliharaan.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, deskripsi, ruangan..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select name="ruangan_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Ruangan</option>
                        @foreach (\App\Models\Ruangan::orderBy('nama')->get() as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 lg:col-span-4 flex items-end space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('pemeliharaan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>


        <!-- Results Count -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Menampilkan {{ $pemeliharaanRuangans->firstItem() }}-{{ $pemeliharaanRuangans->lastItem() }} dari {{ $pemeliharaanRuangans->total() }} pemeliharaan</p>
            @if (request()->anyFilled(['search', 'ruangan_id']))
                <a href="{{ route('pemeliharaan.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Pemeliharaan Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('pemeliharaan.index', ['sort' => 'judul', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                    Judul
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pemeliharaanRuangans as $pemeliharaan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pemeliharaan->judul }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($pemeliharaan->deskripsi, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pemeliharaan->ruangan->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $pemeliharaan->ruangan->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('pemeliharaan.show', $pemeliharaan) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </a>
                                    @can('update', $pemeliharaan)
                                    <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" class="text-yellow-600 hover:text-yellow-900">
                                        Edit
                                    </a>
                                @endcan
                                    @can('delete', $pemeliharaan)
                                        <form method="POST" action="{{ route('pemeliharaan.destroy', $pemeliharaan) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemeliharaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $pemeliharaanRuangans->firstItem() }}-{{ $pemeliharaanRuangans->lastItem() }} dari {{ $pemeliharaanRuangans->total() }} pemeliharaan
            </div>
            {{ $pemeliharaanRuangans->links() }}
        </div>

        <!-- Empty State -->
        @if ($pemeliharaanRuangans->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-tools text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pemeliharaan ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau jadwalkan pemeliharaan baru.</p>
                @can('create', App\Models\PemeliharaanRuangan::class)
                    <a href="{{ route('pemeliharaan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Jadwalkan Pemeliharaan
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>

<x-layouts.app :title="__('Pemeriksaan Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Pemeriksaan Ruangan</h1>
            @can('create', App\Models\PemeriksaanRuangan::class)
                <a href="{{ route('pemeriksaan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Pemeriksaan
                </a>
            @endcan
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('pemeriksaan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kondisi, keperluan, ruangan..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                    <select name="kondisi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kondisi</option>
                        <option value="BAIK" {{ request('kondisi') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                        <option value="BUTUH_PERBAIKAN" {{ request('kondisi') == 'BUTUH_PERBAIKAN' ? 'selected' : '' }}>Butuh Perbaikan</option>
                        <option value="RUSAK" {{ request('kondisi') == 'RUSAK' ? 'selected' : '' }}>Rusak</option>
                    </select>
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
            <p class="text-sm text-gray-600">Menampilkan {{ $pemeriksaanRuangans->firstItem() }}-{{ $pemeriksaanRuangans->lastItem() }} dari {{ $pemeriksaanRuangans->total() }} pemeriksaan</p>
            @if (request()->anyFilled(['search', 'kondisi']))
                <a href="{{ route('pemeriksaan.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Pemeriksaan Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pemeriksaanRuangans as $pemeriksaan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $pemeriksaan->ruangan->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $pemeriksaan->ruangan->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $pemeriksaan->jadwal->keperluan }}</div>
                                    <div class="text-xs text-gray-500">{{ $pemeriksaan->jadwal->peminjam->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($pemeriksaan->isGood()) bg-green-100 text-green-800
                                        @elseif ($pemeriksaan->needsMaintenance()) bg-yellow-100 text-yellow-800
                                        @elseif ($pemeriksaan->isDamaged()) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $pemeriksaan->getKondisiLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pemeriksaan->petugas->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $pemeriksaan->petugas->role }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pemeriksaan->created_at->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('pemeriksaan.show', $pemeriksaan) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </a>
                                    @can('update', $pemeriksaan)
                                        <a href="{{ route('pemeriksaan.edit', $pemeriksaan) }}" class="text-yellow-600 hover:text-yellow-900">
                                            Edit
                                        </a>
                                    @endcan
                                    @can('delete', $pemeriksaan)
                                        <form method="POST" action="{{ route('pemeriksaan.destroy', $pemeriksaan) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemeriksaan ini?')">
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
                Menampilkan {{ $pemeriksaanRuangans->firstItem() }}-{{ $pemeriksaanRuangans->lastItem() }} dari {{ $pemeriksaanRuangans->total() }} pemeriksaan
            </div>
            {{ $pemeriksaanRuangans->links() }}
        </div>

        <!-- Empty State -->
        @if ($pemeriksaanRuangans->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-clipboard-check text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pemeriksaan ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau tambahkan pemeriksaan baru.</p>
                @can('create', App\Models\PemeriksaanRuangan::class)
                    <a href="{{ route('pemeriksaan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Tambah Pemeriksaan
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>
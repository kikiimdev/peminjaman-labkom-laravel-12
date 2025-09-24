<x-layouts.app :title="__('Fasilitas Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Fasilitas Ruangan</h1>
            @if(Auth::user()->role == 'ADMIN')
                <a href="{{ route('fasilitas-ruangans.create', ['ruangan_id' => request('ruangan_id')]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Fasilitas Ruangan
                </a>
            @endif
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('fasilitas-ruangans.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama ruangan atau fasilitas..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select name="ruangan_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                    <select name="fasilitas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Fasilitas</option>
                        @foreach ($fasilitasList as $fasilitas)
                            <option value="{{ $fasilitas->id }}" {{ request('fasilitas_id') == $fasilitas->id ? 'selected' : '' }}>
                                {{ $fasilitas->nama }} ({{ $fasilitas->satuan }})
                            </option>
                        @endforeach
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
            <p class="text-sm text-gray-600">Menampilkan {{ $fasilitasRuangans->firstItem() }}-{{ $fasilitasRuangans->lastItem() }} dari {{ $fasilitasRuangans->total() }} fasilitas ruangan</p>
            @if (request()->anyFilled(['search', 'ruangan_id', 'fasilitas_id']))
                <a href="{{ route('fasilitas-ruangans.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Fasilitas Ruangan Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ruangan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fasilitas
                            </th>
                              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dibuat Pada
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($fasilitasRuangans as $fasilitasRuangan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $fasilitasRuangan->ruangan->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $fasilitasRuangan->ruangan->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $fasilitasRuangan->fasilitas->nama }}</div>
                                </td>
                                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $fasilitasRuangan->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('fasilitas-ruangans.show', $fasilitasRuangan) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->role == 'ADMIN')
                                            <a href="{{ route('fasilitas-ruangans.edit', $fasilitasRuangan) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('fasilitas-ruangans.destroy', $fasilitasRuangan) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ruangan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
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
                Menampilkan {{ $fasilitasRuangans->firstItem() }}-{{ $fasilitasRuangans->lastItem() }} dari {{ $fasilitasRuangans->total() }} fasilitas ruangan
            </div>
            {{ $fasilitasRuangans->links() }}
        </div>

        <!-- Empty State -->
        @if ($fasilitasRuangans->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-tools text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada fasilitas ruangan ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau tambahkan fasilitas ruangan baru.</p>
                @can('create', App\Models\FasilitasRuangan::class)
                    <a href="{{ route('fasilitas-ruangans.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Tambah Fasilitas Ruangan
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>
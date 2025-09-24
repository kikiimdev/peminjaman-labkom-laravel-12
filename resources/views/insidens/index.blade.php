<x-layouts.app :title="__('Insiden')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Insiden</h1>
            @can('create', App\Models\Insiden::class)
                <a href="{{ route('insiden.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Laporkan Insiden
                </a>
            @endcan
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('insiden.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tingkat, keperluan, ruangan..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                    <select name="tingkat" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Tingkat</option>
                        <option value="RENDAH" {{ request('tingkat') == 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                        <option value="SEDANG" {{ request('tingkat') == 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                        <option value="TINGGI" {{ request('tingkat') == 'TINGGI' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" placeholder="Mulai"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" placeholder="Selesai"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>
                <div class="md:col-span-2 lg:col-span-4 flex items-end space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('insiden.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                    <a href="{{ route('insidens.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-chart-bar mr-2"></i>Dashboard
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('insiden.index', ['status' => 'open']) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                <i class="fas fa-exclamation-circle mr-1"></i>Open ({{ \App\Models\Insiden::open()->count() }})
            </a>
            <a href="{{ route('insiden.index', ['status' => 'closed']) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                <i class="fas fa-check-circle mr-1"></i>Closed ({{ \App\Models\Insiden::closed()->count() }})
            </a>
            <a href="{{ route('insiden.index', ['tingkat' => 'TINGGI']) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                <i class="fas fa-fire mr-1"></i>Tingkat Tinggi ({{ \App\Models\Insiden::where('tingkat', 'TINGGI')->count() }})
            </a>
        </div>

        <!-- Results Count -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Menampilkan {{ $insidens->firstItem() }}-{{ $insidens->lastItem() }} dari {{ $insidens->total() }} insiden</p>
            @if (request()->anyFilled(['search', 'tingkat', 'status', 'ruangan_id', 'tanggal_mulai', 'tanggal_selesai']))
                <a href="{{ route('insiden.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Insiden Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('insiden.index', ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                    Tanggal
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditangani Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($insidens as $insiden)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $insiden->created_at->locale('id')->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $insiden->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $insiden->ruangan->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $insiden->ruangan->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($insiden->isLowSeverity()) bg-green-100 text-green-800
                                        @elseif ($insiden->isMediumSeverity()) bg-yellow-100 text-yellow-800
                                        @elseif ($insiden->isHighSeverity()) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $insiden->getTingkatLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $insiden->pelapor->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $insiden->pelapor->role }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $insiden->ditangani_oleh ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($insiden->isOpen()) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $insiden->isOpen() ? 'Open' : 'Closed' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('insiden.show', $insiden) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </a>
                                    @can('update', $insiden)
                                        @if ($insiden->isOpen())
                                            <a href="{{ route('insiden.edit', $insiden) }}" class="text-yellow-600 hover:text-yellow-900">
                                                Edit
                                            </a>
                                        @endif
                                    @endcan
                                    @can('delete', $insiden)
                                        <form method="POST" action="{{ route('insiden.destroy', $insiden) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus insiden ini?')">
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
                Menampilkan {{ $insidens->firstItem() }}-{{ $insidens->lastItem() }} dari {{ $insidens->total() }} insiden
            </div>
            {{ $insidens->links() }}
        </div>

        <!-- Empty State -->
        @if ($insidens->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-exclamation-triangle text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada insiden ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau laporkan insiden baru.</p>
                @can('create', App\Models\Insiden::class)
                    <a href="{{ route('insiden.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Laporkan Insiden
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>

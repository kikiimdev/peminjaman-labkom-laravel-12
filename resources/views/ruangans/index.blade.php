<x-layouts.app :title="__('Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Ruangan</h1>
            @can('create', App\Models\Ruangan::class)
                <a href="{{ route('ruangan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Ruangan
                </a>
            @endcan
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('ruangan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau lokasi..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik</label>
                    <select name="pemilik_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Pemilik</option>
                        @foreach ($ruangans->pluck('pemilik')->unique() as $pemilik)
                            <option value="{{ $pemilik->id }}" {{ request('pemilik_id') == $pemilik->id ? 'selected' : '' }}>
                                {{ $pemilik->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama</option>
                        <option value="kapasitas" {{ request('sort') == 'kapasitas' ? 'selected' : '' }}>Kapasitas</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
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
            <p class="text-sm text-gray-600">Menampilkan {{ $ruangans->firstItem() }}-{{ $ruangans->lastItem() }} dari {{ $ruangans->total() }} ruangan</p>
            @if (request()->anyFilled(['search', 'pemilik_id', 'sort']))
                <a href="{{ route('ruangan.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Ruangan Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($ruangans as $ruangan)
                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $ruangan->nama }}</h3>
                                <p class="text-sm text-gray-600">{{ $ruangan->kode }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($ruangan->status == 'TERSEDIA') bg-green-100 text-green-800
                                @elseif ($ruangan->status == 'TERPAKAI') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $ruangan->status }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                <span>{{ $ruangan->lokasi }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users w-4 mr-2"></i>
                                <span>{{ $ruangan->kapasitas }} orang</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user w-4 mr-2"></i>
                                <span>{{ $ruangan->pemilik->name }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-tools w-4 mr-2"></i>
                                <span>{{ $ruangan->fasilitas->count() }} fasilitas</span>
                            </div>
                        </div>

                        @if(Auth::user()->role == "ADMIN" && $ruangan->jadwals->count() > 0)
                            @php
                                $approvedJadwals = $ruangan->jadwals->where('status', 'DISETUJUI')->take(2);
                            @endphp
                            @if ($approvedJadwals->count() > 0)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 mb-2">Jadwal Terakhir:</p>
                                    <div class="space-y-1">
                                        @foreach ($approvedJadwals as $jadwal)
                                            <div class="text-xs text-gray-600 bg-green-50 rounded px-2 py-1 border-l-2 border-green-500">
                                                <div class="font-medium">{{ $jadwal->keperluan }}</div>
                                                <div>{{ $jadwal->peminjam->name }}</div>
                                                @if ($jadwal->tanggalJadwals->count() > 0)
                                                    @php
                                                        $latestTanggal = $jadwal->tanggalJadwals->sortByDesc('tanggal')->first();
                                                    @endphp
                                                    <div class="text-green-600">
                                                        {{ $latestTanggal->tanggal->locale('id')->translatedFormat('d M Y') }}
                                                        @if ($latestTanggal->jam_mulai && $latestTanggal->jam_berakhir)
                                                            • {{ $latestTanggal->jam_mulai }} - {{ $latestTanggal->jam_berakhir }}
                                                        @elseif ($latestTanggal->jam_mulai)
                                                            • {{ $latestTanggal->jam_mulai }}
                                                        @else
                                                            • Full Day
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="flex space-x-2">
                            <a href="{{ route('ruangan.show', $ruangan) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm font-medium text-center">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            @can('update', $ruangan)
                                <a href="{{ route('ruangan.edit', $ruangan) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium text-center">
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
                Menampilkan {{ $ruangans->firstItem() }}-{{ $ruangans->lastItem() }} dari {{ $ruangans->total() }} ruangan
            </div>
            {{ $ruangans->links() }}
        </div>

        <!-- Empty State -->
        @if ($ruangans->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-door-open text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada ruangan ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau tambahkan ruangan baru.</p>
                @can('create', App\Models\Ruangan::class)
                    <a href="{{ route('ruangan.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Tambah Ruangan
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>
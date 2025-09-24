<x-layouts.app :title="__('Detail Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('ruangan.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $ruangan->nama }}</h1>
            </div>
            <div class="flex space-x-2">
                @can('update', $ruangan)
                    <a href="{{ route('ruangan.edit', $ruangan) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                @can('delete', $ruangan)
                    <form method="POST" action="{{ route('ruangan.destroy', $ruangan) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Main Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $ruangan->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="font-medium text-gray-900">{{ $ruangan->lokasi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pemilik</p>
                            <p class="font-medium text-gray-900">{{ $ruangan->pemilik->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dibuat Pada</p>
                            <p class="font-medium text-gray-900">{{ $ruangan->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Schedules -->
                @if(Auth::user()->role == "ADMIN")
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Jadwal Terakhir</h2>
                        <a href="{{ route('jadwal.index', ['ruangan_id' => $ruangan->id, 'status' => 'DISETUJUI']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Lihat Semua
                        </a>
                    </div>
                    @php
                        $approvedJadwals = $ruangan->jadwals->where('status', 'DISETUJUI')->take(5);
                    @endphp
                    @if ($approvedJadwals->count() > 0)
                        <div class="space-y-3">
                            @foreach ($approvedJadwals as $jadwal)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $jadwal->keperluan }}</p>
                                        <p class="text-sm text-gray-600">{{ $jadwal->peminjam->name }}</p>
                                        @if ($jadwal->tanggalJadwals->count() > 0)
                                            @php
                                                $latestTanggal = $jadwal->tanggalJadwals->sortByDesc('tanggal')->first();
                                            @endphp
                                            <p class="text-xs text-gray-500">
                                                {{ $latestTanggal->tanggal->locale('id')->translatedFormat('d F Y') }}
                                                @if ($latestTanggal->jam_mulai && $latestTanggal->jam_berakhir)
                                                    • {{ $latestTanggal->jam_mulai }} - {{ $latestTanggal->jam_berakhir }}
                                                @elseif ($latestTanggal->jam_mulai)
                                                    • {{ $latestTanggal->jam_mulai }}
                                                @else
                                                    • Full Day
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500">Tanggal tidak tersedia</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        DISETUJUI
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada jadwal yang disetujui untuk ruangan ini</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Fasilitas</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $ruangan->fasilitas->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Jadwal</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $ruangan->jadwals->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Jadwal Aktif</span>
                            <span class="text-lg font-semibold text-green-600">
                                {{ $ruangan->jadwals->whereIn('status', ['MENUNGGU', 'DISETUJUI'])->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('jadwal.create', ['ruangan_id' => $ruangan->id]) }}"
                           class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-plus mr-2"></i>Buat Jadwal
                        </a>
                        @if(Auth::user()->role == "ADMIN")
                        <a href="{{ route('fasilitas-ruangans.index', ['ruangan_id' => $ruangan->id]) }}"
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-tools mr-2"></i>Kelola Fasilitas
                        </a>
                        <a href="{{ route('pemeriksaan.create', ['ruangan_id' => $ruangan->id]) }}"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-check-circle mr-2"></i>Tambah Pemeriksaan
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Facilities Section -->
        @if ($ruangan->fasilitas->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Fasilitas</h2>
                    <a href="{{ route('fasilitas-ruangans.index', ['ruangan_id' => $ruangan->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                        Kelola Fasilitas
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($ruangan->fasilitas as $fasilitas)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-indigo-100 p-2 rounded-lg">
                                <i class="fas fa-tools text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $fasilitas->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $fasilitas->kategori }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>

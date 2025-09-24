<x-layouts.app :title="__('Detail Pemeriksaan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('pemeriksaan.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pemeriksaan</h1>
            </div>
            <div class="flex space-x-2">
                @can('update', $pemeriksaan_ruangan)
                    <a href="{{ route('pemeriksaan.edit', $pemeriksaan_ruangan) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                @can('delete', $pemeriksaan_ruangan)
                    <form method="POST" action="{{ route('pemeriksaan.destroy', $pemeriksaan_ruangan) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemeriksaan ini?')">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemeriksaan</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Kondisi</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if ($pemeriksaan_ruangan->isGood()) bg-green-100 text-green-800
                                @elseif ($pemeriksaan_ruangan->needsMaintenance()) bg-yellow-100 text-yellow-800
                                @elseif ($pemeriksaan_ruangan->isDamaged()) bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $pemeriksaan_ruangan->getKondisiLabel() }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Petugas</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->petugas->name }}</p>
                            <p class="text-sm text-gray-600">{{ $pemeriksaan_ruangan->petugas->role }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Diperiksa Pada</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Related Jadwal -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Terkait</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Keperluan</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->jadwal->keperluan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Peminjam</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->jadwal->peminjam->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status Jadwal</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($pemeriksaan_ruangan->jadwal->status == 'DISETUJUI') bg-green-100 text-green-800
                                @elseif ($pemeriksaan_ruangan->jadwal->status == 'MENUNGGU') bg-yellow-100 text-yellow-800
                                @elseif ($pemeriksaan_ruangan->jadwal->status == 'DITOLAK') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $pemeriksaan_ruangan->jadwal->status }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('jadwal.show', $pemeriksaan_ruangan->jadwal) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Lihat Detail Jadwal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column - Ruangan Info -->
            <div class="space-y-6">
                <!-- Ruangan Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Ruangan</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->ruangan->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->ruangan->lokasi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pemilik</p>
                            <p class="font-medium text-gray-900">{{ $pemeriksaan_ruangan->ruangan->pemilik->name }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('ruangan.show', $pemeriksaan_ruangan->ruangan) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Lihat Detail Ruangan
                        </a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="space-y-3">
                        @if ($pemeriksaan_ruangan->needsMaintenance() || $pemeriksaan_ruangan->isDamaged())
                            <a href="{{ route('pemeliharaan.create', ['ruangan_id' => $pemeriksaan_ruangan->ruangan_id, 'pemeriksaan_id' => $pemeriksaan_ruangan->id]) }}"
                               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                                <i class="fas fa-tools mr-2"></i>Buat Laporan Pemeliharaan
                            </a>
                        @endif
                        <a href="{{ route('ruangan.show', $pemeriksaan_ruangan->ruangan) }}"
                           class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-door-open mr-2"></i>Lihat Detail Ruangan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
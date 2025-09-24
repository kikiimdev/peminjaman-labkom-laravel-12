<x-layouts.app :title="__('Detail Fasilitas')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('fasilitas.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $fasilitas->nama }}</h1>
            </div>
            <div class="flex space-x-2">
                @can('update', $fasilitas)
                    <a href="{{ route('fasilitas.edit', $fasilitas) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                @can('delete', $fasilitas)
                    <form method="POST" action="{{ route('fasilitas.destroy', $fasilitas) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Fasilitas</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Satuan</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas->satuan }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Dibuat Pada</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Rooms Using This Facility -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Ruangan yang Menggunakan Fasilitas Ini</h2>
                        <a href="{{ route('ruangan.index', ['search' => $fasilitas->nama]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Lihat Semua Ruangan
                        </a>
                    </div>
                    @if ($fasilitas->fasilitasRuangans->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($fasilitas->fasilitasRuangans as $fasilitasRuangan)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $fasilitasRuangan->ruangan->nama }}</p>
                                        <p class="text-sm text-gray-600">{{ $fasilitasRuangan->ruangan->lokasi }}</p>
                                        <p class="text-xs text-gray-500">Ditambahkan: {{ $fasilitasRuangan->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('ruangan.show', $fasilitasRuangan->ruangan) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Fasilitas ini belum digunakan di ruangan mana pun</p>
                    @endif
                </div>
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Ruangan</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $fasilitas->fasilitasRuangans->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Dibuat Pada</span>
                            <span class="text-sm font-medium text-gray-900">{{ $fasilitas->created_at->locale('id')->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="space-y-3">
                        @can('update', $fasilitas)
                            <a href="{{ route('fasilitas.edit', $fasilitas) }}"
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                                <i class="fas fa-edit mr-2"></i>Edit Fasilitas
                            </a>
                        @endcan
                        <a href="{{ route('ruangan.index') }}"
                           class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-door-open mr-2"></i>Lihat Semua Ruangan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
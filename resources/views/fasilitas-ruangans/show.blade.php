<x-layouts.app :title="__('Detail Fasilitas Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('fasilitas-ruangans.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Fasilitas Ruangan</h1>
            </div>
            <div class="flex space-x-2">
                @if(Auth::user()->role == 'ADMIN')
                    <a href="{{ route('fasilitas-ruangans.edit', $fasilitas_ruangan) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('fasilitas-ruangans.destroy', $fasilitas_ruangan) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ruangan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Main Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->ruangan->nama }}</p>
                            <p class="text-sm text-gray-600">{{ $fasilitas_ruangan->ruangan->lokasi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fasilitas</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->fasilitas->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->jumlah }} unit</p>
                        </div>
                        @if ($fasilitas_ruangan->keterangan)
                            <div>
                                <p class="text-sm text-gray-600">Keterangan</p>
                                <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->keterangan }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600">Dibuat Pada</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        @if ($fasilitas_ruangan->updated_at->ne($fasilitas_ruangan->created_at))
                            <div>
                                <p class="text-sm text-gray-600">Diperbarui Pada</p>
                                <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Terkait</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Fasilitas di Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->ruangan->fasilitas->count() }} jenis fasilitas</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pemilik Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->ruangan->pemilik->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kapasitas Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->ruangan->kapasitas }} orang</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Jumlah Unit</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $fasilitas_ruangan->jumlah }}</span>
                        </div>
                            <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Usia Penambahan</span>
                            <span class="text-lg font-semibold text-green-600">{{ $fasilitas_ruangan->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('ruangan.show', $fasilitas_ruangan->ruangan) }}"
                           class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-door-open mr-2"></i>Lihat Detail Ruangan
                        </a>
                        <a href="{{ route('fasilitas-ruangans.index', ['ruangan_id' => $fasilitas_ruangan->ruangan_id]) }}"
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                            <i class="fas fa-tools mr-2"></i>Lihat Semua Fasilitas Ruangan
                        </a>
                        @if(Auth::user()->role == 'ADMIN')
                            <a href="{{ route('fasilitas-ruangans.create', ['ruangan_id' => $fasilitas_ruangan->ruangan_id]) }}"
                               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium text-center block">
                                <i class="fas fa-plus mr-2"></i>Tambah Fasilitas Lain
                            </a>
                        @endif
                    </div>
                </div>

              </div>
        </div>

        <!-- Other Facilities in Same Room -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Fasilitas Lain di Ruangan Ini</h2>
                <span class="text-sm text-gray-500">{{ $fasilitas_ruangan->ruangan->fasilitas->count() }} fasilitas</span>
            </div>
            @if ($otherFacilities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($otherFacilities as $otherFasilitas)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="bg-indigo-100 p-2 rounded-lg">
                                <i class="fas fa-tools text-indigo-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $otherFasilitas->fasilitas->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $otherFasilitas->jumlah }} unit</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Tidak ada fasilitas lain di ruangan ini</p>
            @endif
        </div>
    </div>
</x-layouts.app>
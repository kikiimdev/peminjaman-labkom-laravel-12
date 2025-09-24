<x-layouts.app :title="__('Detail Pemeliharaan Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pemeliharaan Ruangan</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap jadwal pemeliharaan ruangan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('pemeliharaan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                @can('update', $pemeliharaan)
                    <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" class="text-yellow-600 hover:text-yellow-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
            </div>
        </div>

        <!-- Header Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $pemeliharaan->judul }}</h2>
                <p class="text-gray-600 mt-1">{{ $pemeliharaan->ruangan->nama }} - {{ $pemeliharaan->ruangan->lokasi }}</p>
            </div>
        </div>

  
        <!-- Informasi Detail -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemeliharaan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ruangan</span>
                        <span class="font-medium">
                            <a href="{{ route('ruangan.show', $pemeliharaan->ruangan) }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $pemeliharaan->ruangan->nama }}
                            </a>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pemilik Ruangan</span>
                        <span class="font-medium">{{ $pemeliharaan->ruangan->pemilik->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat Pada</span>
                        <span class="font-medium">{{ $pemeliharaan->created_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diperbarui Pada</span>
                        <span class="font-medium">{{ $pemeliharaan->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi Pemeliharaan</h3>
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $pemeliharaan->deskripsi }}</p>
            </div>
        </div>
    </div>
</x-layouts.app>
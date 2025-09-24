<x-layouts.app :title="__('Edit Fasilitas Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('fasilitas-ruangans.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Fasilitas Ruangan</h1>
            </div>
        </div>

        <!-- Current Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Saat Ini</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Ruangan</p>
                    <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->ruangan->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fasilitas</p>
                    <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->fasilitas->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah</p>
                    <p class="font-medium text-gray-900">{{ $fasilitas_ruangan->jumlah }} unit</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('fasilitas-ruangans.update', $fasilitas_ruangan) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <!-- Ruangan (Disabled - cannot change room for existing facility) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ruangan
                        </label>
                        <input
                            type="text"
                            value="{{ $fasilitas_ruangan->ruangan->nama }} - {{ $fasilitas_ruangan->ruangan->lokasi }}"
                            disabled
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-500"
                        >
                        <p class="mt-1 text-sm text-gray-500">Ruangan tidak dapat diubah untuk fasilitas yang sudah ada</p>
                    </div>

                    <!-- Fasilitas (Disabled - cannot change facility type for existing) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fasilitas
                        </label>
                        <input
                            type="text"
                            value="{{ $fasilitas_ruangan->fasilitas->nama }} ({{ $fasilitas_ruangan->fasilitas->satuan }})"
                            disabled
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-500"
                        >
                        <p class="mt-1 text-sm text-gray-500">Jenis fasilitas tidak dapat diubah. Hapus dan buat baru jika perlu mengubah jenis fasilitas.</p>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-6">
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="jumlah"
                            name="jumlah"
                            min="1"
                            value="{{ old('jumlah', $fasilitas_ruangan->jumlah) }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan jumlah fasilitas"
                        >
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Update jumlah fasilitas yang tersedia di ruangan ini</p>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Keterangan tambahan tentang fasilitas ini (opsional)"
                        >{{ old('keterangan', $fasilitas_ruangan->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warning about changing facility type -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Catatan Penting</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Jika Anda perlu mengubah jenis ruangan atau fasilitas, Anda harus menghapus entri ini dan membuat yang baru. Ini untuk menjaga konsistensi data.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <div class="flex space-x-3">
                        <a href="{{ route('fasilitas-ruangans.show', $fasilitas_ruangan) }}" class="text-gray-700 hover:text-gray-900 font-medium">
                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                        </a>
                        <a href="{{ route('fasilitas-ruangans.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Update Fasilitas Ruangan
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Alternatif</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('ruangan.show', $fasilitas_ruangan->ruangan) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-door-open mr-2"></i>Lihat Detail Ruangan
                </a>
                <a href="{{ route('fasilitas-ruangans.index', ['ruangan_id' => $fasilitas_ruangan->ruangan_id]) }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-tools mr-2"></i>Lihat Semua Fasilitas Ruangan
                </a>
                @can('create', App\Models\FasilitasRuangan::class)
                    <a href="{{ route('fasilitas-ruangans.create', ['ruangan_id' => $fasilitas_ruangan->ruangan_id]) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Tambah Fasilitas Lain
                    </a>
                @endcan
            </div>
        </div>

        <!-- Error Messages -->
        <x-error-messages />
    </div>
</x-layouts.app>
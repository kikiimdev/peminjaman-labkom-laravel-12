<x-layouts.app :title="__('Edit Pemeliharaan Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Pemeliharaan Ruangan</h1>
                <p class="text-gray-600 mt-1">Perbarui informasi jadwal pemeliharaan ruangan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('pemeliharaan.show', $pemeliharaan) }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('pemeliharaan.update', $pemeliharaan) }}" class="space-y-6 p-6">
                @csrf
                @method('PUT')

                <x-error-messages />

                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informasi Pemeliharaan -->
                <div>
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pemeliharaan <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $pemeliharaan->judul) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pemeliharaan <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi', $pemeliharaan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Informasi Ruangan (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Ruangan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Ruangan:</span>
                            <span class="font-medium ml-2">{{ $pemeliharaan->ruangan->nama }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Lokasi:</span>
                            <span class="font-medium ml-2">{{ $pemeliharaan->ruangan->lokasi }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('pemeliharaan.show', $pemeliharaan) }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Perbarui Pemeliharaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
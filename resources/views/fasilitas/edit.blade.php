<x-layouts.app :title="__('Edit Fasilitas')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('fasilitas.show', $fasilitas->id) }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Fasilitas</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('fasilitas.update', $fasilitas) }}" method="POST" class="space-y-6">
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

                <!-- Nama Fasilitas -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Fasilitas <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $fasilitas->nama) }}"
                        required
                        maxlength="100"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Contoh: Proyektor, Komputer, Meja"
                    >
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Satuan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="satuan"
                        name="satuan"
                        value="{{ old('satuan', $fasilitas->satuan) }}"
                        required
                        maxlength="50"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Contoh: Unit, Set, Buah"
                    >
                    @error('satuan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Usage Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900">Informasi Penggunaan</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Fasilitas ini digunakan oleh {{ $fasilitas->fasilitasRuangans->count() }} ruangan.
                                Mengubah nama atau satuan tidak akan mempengaruhi data yang sudah ada.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('fasilitas.show', $fasilitas->id) }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Update Fasilitas
                    </button>
                </div>
            </form>
        </div>

        <!-- Related Rooms Section -->
        @if ($fasilitas->fasilitasRuangans->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ruangan yang Menggunakan Fasilitas Ini</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($fasilitas->fasilitasRuangans as $fasilitasRuangan)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $fasilitasRuangan->ruangan->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $fasilitasRuangan->ruangan->lokasi }}</p>
                                <p class="text-xs text-gray-500">Ditambahkan: {{ $fasilitasRuangan->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('ruangan.show', $fasilitasRuangan->ruangan->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
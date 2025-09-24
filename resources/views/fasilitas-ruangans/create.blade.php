<x-layouts.app :title="__('Tambah Fasilitas Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('fasilitas-ruangans.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Fasilitas Ruangan</h1>
            @if($selectedRuanganId)
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Menambahkan fasilitas untuk ruangan: {{ $ruangans->where('id', $selectedRuanganId)->first()->nama }}
                    </p>
                </div>
            @endif
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('fasilitas-ruangans.store') }}" class="space-y-6">
                @csrf
                <div class="p-6">
                    <!-- Ruangan -->
                    <div class="mb-6">
                        <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Ruangan <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="ruangan_id"
                            name="ruangan_id"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $selectedRuanganId) == $ruangan->id ? 'selected' : '' }}>
                                    {{ $ruangan->nama }} - {{ $ruangan->lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fasilitas -->
                    <div class="mb-6">
                        <label for="fasilitas_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Fasilitas <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="fasilitas_id"
                            name="fasilitas_id"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Pilih Fasilitas</option>
                            @foreach ($fasilitas as $fasilitas)
                                <option value="{{ $fasilitas->id }}" {{ old('fasilitas_id') == $fasilitas->id ? 'selected' : '' }}>
                                    {{ $fasilitas->nama }} ({{ $fasilitas->satuan }})
                                </option>
                            @endforeach
                        </select>
                        @error('fasilitas_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                            value="{{ old('jumlah', 1) }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan jumlah fasilitas"
                        >
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Masukkan jumlah fasilitas yang tersedia di ruangan ini</p>
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
                        >{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <a href="{{ route('fasilitas-ruangans.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Fasilitas Ruangan
                    </button>
                </div>
            </form>
        </div>

        <!-- Error Messages -->
        <x-error-messages />
    </div>
</x-layouts.app>
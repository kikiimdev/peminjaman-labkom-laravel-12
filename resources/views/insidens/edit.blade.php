<x-layouts.app :title="__('Edit Insiden')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Insiden</h1>
                <p class="text-gray-600 mt-1">Perbarui informasi laporan insiden</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('insiden.show', $insiden) }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('insiden.update', $insiden) }}" class="space-y-6 p-6">
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

                <!-- Informasi Kejadian -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Insiden <span class="text-red-500">*</span></label>
                        <select id="tingkat" name="tingkat" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="RENDAH" {{ old('tingkat', $insiden->tingkat) == 'RENDAH' ? 'selected' : '' }}>Rendah - Masalah kecil, tidak mengganggu aktivitas</option>
                            <option value="SEDANG" {{ old('tingkat') == 'SEDANG' ? 'selected' : '' }}>Sedang - Mengganggu aktivitas, butuh perhatian</option>
                            <option value="TINGGI" {{ old('tingkat') == 'TINGGI' ? 'selected' : '' }}>Tinggi - Bahaya, memerlukan penanganan segera</option>
                        </select>
                        @error('tingkat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="ditangani_oleh" class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                        <input type="text" id="ditangani_oleh" name="ditangani_oleh"
                               value="{{ old('ditangani_oleh', $insiden->ditangani_oleh) }}"
                               placeholder="Nama penanggung jawab (kosongkan jika belum ditugaskan)"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('ditangani_oleh')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kejadian <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="6" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi', $insiden->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Lainnya (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Informasi Lainnya</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Ruangan:</span>
                            <span class="font-medium ml-2">{{ $insiden->ruangan->nama }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Pelapor:</span>
                            <span class="font-medium ml-2">{{ $insiden->pelapor->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Tanggal Kejadian:</span>
                            <span class="font-medium ml-2">{{ $insiden->created_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium ml-2">{{ $insiden->isOpen() ? 'Open' : 'Closed' }}</span>
                        </div>
                        @if ($insiden->jadwal_id)
                            <div class="md:col-span-2">
                                <span class="text-gray-600">Terkait Jadwal:</span>
                                <span class="font-medium ml-2">{{ $insiden->jadwal->kode_peminjaman }} - {{ $insiden->jadwal->keperluan }}</span>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="ruangan_id" value="{{ $insiden->ruangan_id }}">
                    <input type="hidden" name="pelapor_id" value="{{ $insiden->pelapor_id }}">
                    @if ($insiden->jadwal_id)
                        <input type="hidden" name="jadwal_id" value="{{ $insiden->jadwal_id }}">
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('insiden.show', $insiden) }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Perbarui Insiden
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
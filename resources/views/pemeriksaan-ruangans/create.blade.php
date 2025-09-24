<x-layouts.app :title="__('Tambah Pemeriksaan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('pemeriksaan.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Pemeriksaan</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('pemeriksaan.store') }}" method="POST" class="space-y-6">
                @csrf

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

                <!-- Jadwal -->
                <div>
                    <label for="jadwal_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Jadwal <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="jadwal_id"
                        name="jadwal_id"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Jadwal</option>
                        @foreach ($jadwals as $jadwalOption)
                            <option value="{{ $jadwalOption->id }}"
                                    {{ old('jadwal_id', $jadwal ? $jadwal->id : '') == $jadwalOption->id ? 'selected' : '' }}
                                    {{ $jadwalOption->status != 'DISETUJUI' ? 'disabled' : '' }}>
                                {{ $jadwalOption->keperluan }} - {{ $jadwalOption->ruangan->nama }}
                                ({{ $jadwalOption->peminjam->name }})
                                @if ($jadwalOption->status != 'DISETUJUI')
                                    [{{ $jadwalOption->status }}]
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('jadwal_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ruangan (Auto-filled if jadwal is selected) -->
                <div>
                    <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="ruangan_id"
                        name="ruangan_id"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        {{ $jadwal ? 'disabled' : '' }}>
                        <option value="">Pilih Ruangan</option>
                        @foreach ($ruangans as $ruanganOption)
                            <option value="{{ $ruanganOption->id }}"
                                    {{ old('ruangan_id', $ruangan ? $ruangan->id : '') == $ruanganOption->id ? 'selected' : '' }}>
                                {{ $ruanganOption->nama }} - {{ $ruanganOption->lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('ruangan_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi -->
                <div>
                    <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="kondisi"
                        name="kondisi"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kondisi</option>
                        <option value="BAIK" {{ old('kondisi') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                        <option value="BUTUH_PERBAIKAN" {{ old('kondisi') == 'BUTUH_PERBAIKAN' ? 'selected' : '' }}>Butuh Perbaikan</option>
                        <option value="RUSAK" {{ old('kondisi') == 'RUSAK' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    @error('kondisi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Petugas (Auto-filled to current user) -->
                <input type="hidden" name="petugas_id" value="{{ auth()->id() }}">

                <!-- Info Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900">Informasi Pemeriksaan</h4>
                            <ul class="text-sm text-blue-700 mt-1 space-y-1">
                                <li>• Pemeriksaan akan dicatat atas nama Anda sebagai petugas</li>
                                <li>• Hanya jadwal dengan status DISETUJUI yang dapat diperiksa</li>
                                <li>• Pilih kondisi ruangan secara objektif</li>
                                <li>• Jika kondisi BUTUH_PERBAIKAN atau RUSAK, dapat dibuat laporan pemeliharaan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('pemeriksaan.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Pemeriksaan
                    </button>
                </div>
            </form>
        </div>

        <!-- JavaScript for auto-fill ruangan -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const jadwalSelect = document.getElementById('jadwal_id');
                const ruanganSelect = document.getElementById('ruangan_id');

                jadwalSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value && !selectedOption.disabled) {
                        // Ruangan will be auto-filled by server based on jadwal
                        ruanganSelect.disabled = true;
                    } else {
                        ruanganSelect.disabled = false;
                    }
                });
            });
        </script>
    </div>
</x-layouts.app>
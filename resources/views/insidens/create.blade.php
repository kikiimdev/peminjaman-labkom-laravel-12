<x-layouts.app :title="__('Laporkan Insiden')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporkan Insiden</h1>
                <p class="text-gray-600 mt-1">Laporkan kejadian atau masalah yang terjadi di ruangan laboratorium</p>
            </div>
            <a href="{{ route('insiden.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('insiden.store') }}" class="space-y-6 p-6">
                @csrf
                @method('POST')

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
                        <label for="jadwal_id" class="block text-sm font-medium text-gray-700 mb-1">Terkait Jadwal (Opsional)</label>
                        <select id="jadwal_id" name="jadwal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Jadwal (jika ada)</option>
                            @foreach ($jadwals as $j)
                                <option value="{{ $j->id }}" {{ old('jadwal_id', $jadwal?->id) == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode_peminjaman }} - {{ $j->keperluan }} ({{ $j->ruangan->nama }})
                                </option>
                            @endforeach
                        </select>
                        @error('jadwal_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                        <select id="ruangan_id" name="ruangan_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangans as $r)
                                <option value="{{ $r->id }}" {{ old('ruangan_id', $ruangan?->id) == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama }} - {{ $r->lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Detail Insiden -->
                <div>
                    <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Insiden <span class="text-red-500">*</span></label>
                    <select id="tingkat" name="tingkat" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Tingkat</option>
                        <option value="RENDAH" {{ old('tingkat') == 'RENDAH' ? 'selected' : '' }}>Rendah - Masalah kecil, tidak mengganggu aktivitas</option>
                        <option value="SEDANG" {{ old('tingkat') == 'SEDANG' ? 'selected' : '' }}>Sedang - Mengganggu aktivitas, butuh perhatian</option>
                        <option value="TINGGI" {{ old('tingkat') == 'TINGGI' ? 'selected' : '' }}>Tinggi - Bahaya, memerlukan penanganan segera</option>
                    </select>
                    @error('tingkat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kejadian <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="6" required
                              placeholder="Jelaskan secara detail kronologi kejadian, apa yang terjadi, dampak yang ditimbulkan, dan informasi penting lainnya..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penanggung Jawab (Opsional) -->
                <div>
                    <label for="ditangani_oleh" class="block text-sm font-medium text-gray-700 mb-1">Ditangani Oleh (Opsional)</label>
                    <select id="ditangani_oleh" name="ditangani_oleh" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Belum ditugaskan</option>
                        @foreach (\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}" {{ old('ditangani_oleh') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika ingin ditugaskan kemudian</p>
                    @error('ditangani_oleh')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Pelapor (Auto-filled) -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Informasi Pelapor</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700">Nama:</span>
                            <span class="font-medium text-blue-900 ml-2">{{ auth()->user()->name }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700">Role:</span>
                            <span class="font-medium text-blue-900 ml-2">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    <input type="hidden" name="pelapor_id" value="{{ auth()->id() }}">
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('insiden.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
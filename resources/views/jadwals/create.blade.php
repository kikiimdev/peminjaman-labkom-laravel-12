<x-layouts.app :title="__('Ajukan Peminjaman')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('jadwal.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Ajukan Peminjaman</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('jadwal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                <!-- Keperluan -->
                <div>
                    <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan Peminjaman <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="keperluan"
                        name="keperluan"
                        rows="4"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Jelaskan keperluan peminjaman ruangan...">{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ruangan -->
                <div>
                    <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="ruangan_id"
                        name="ruangan_id"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ old('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }} - {{ $ruangan->lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('ruangan_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Jadwal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Jadwal <span class="text-red-500">*</span>
                    </label>
                    <div id="tanggal-jadwal-container" class="space-y-4">
                        <!-- Initial tanggal jadwal row -->
                        <div class="tanggal-jadwal-row grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-lg">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                                <input
                                    type="date"
                                    name="tanggal_jadwals[0][tanggal]"
                                    required
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('tanggal_jadwals.0.tanggal') }}">
                                @error('tanggal_jadwals.0.tanggal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jam Mulai <span class="text-gray-400">(opsional)</span></label>
                                <input
                                    type="time"
                                    name="tanggal_jadwals[0][jam_mulai]"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('tanggal_jadwals.0.jam_mulai') }}"
                                    placeholder="HH:MM">
                                @error('tanggal_jadwals.0.jam_mulai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-xs mt-1">Isi jika ada waktu spesifik</p>
                            </div>
                            <div class="flex items-end space-x-2">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Jam Berakhir <span class="text-gray-400">(opsional)</span></label>
                                    <input
                                        type="time"
                                        name="tanggal_jadwals[0][jam_berakhir]"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('tanggal_jadwals.0.jam_berakhir') }}"
                                        placeholder="HH:MM">
                                    @error('tanggal_jadwals.0.jam_berakhir')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">Isi jika ada waktu spesifik</p>
                                </div>
                                <button type="button" onclick="removeTanggalJadwal(this)" class="remove-btn p-2 text-red-600 hover:text-red-800 hidden">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add more button -->
                    <div class="mt-4">
                        <button type="button" onclick="addTanggalJadwal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>Tambah Tanggal
                        </button>
                    </div>
                    @error('tanggal_jadwals')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

  
                <!-- Info Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900">Informasi Penting</h4>
                            <ul class="text-sm text-blue-700 mt-1 space-y-1">
                                <li>• Peminjaman akan diajukan dengan status MENUNGGU</li>
                                <li>• Menunggu persetujuan dari pemilik ruangan</li>
                                <li>• Anda dapat mengedit peminjaman selama status masih MENUNGGU</li>
                                <li>• Tambahkan minimal satu tanggal dan waktu peminjaman</li>
                                <li>• Anda dapat menambahkan beberapa tanggal jika diperlukan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('jadwal.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for dynamic tanggal jadwal -->
    <script>
        let tanggalJadwalCount = 1;

        function addTanggalJadwal() {
            const container = document.getElementById('tanggal-jadwal-container');
            const newIndex = tanggalJadwalCount++;

            const newRow = document.createElement('div');
            newRow.className = 'tanggal-jadwal-row grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-lg';
            newRow.innerHTML = `
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                    <input
                        type="date"
                        name="tanggal_jadwals[${newIndex}][tanggal]"
                        required
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jam Mulai <span class="text-gray-400">(opsional)</span></label>
                    <input
                        type="time"
                        name="tanggal_jadwals[${newIndex}][jam_mulai]"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value=""
                        placeholder="HH:MM">
                    <p class="text-gray-500 text-xs mt-1">Isi jika ada waktu spesifik</p>
                </div>
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jam Berakhir <span class="text-gray-400">(opsional)</span></label>
                        <input
                            type="time"
                            name="tanggal_jadwals[${newIndex}][jam_berakhir]"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value=""
                            placeholder="HH:MM">
                        <p class="text-gray-500 text-xs mt-1">Isi jika ada waktu spesifik</p>
                    </div>
                    <button type="button" onclick="removeTanggalJadwal(this)" class="remove-btn p-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            container.appendChild(newRow);
            updateRemoveButtons();
        }

        function removeTanggalJadwal(button) {
            const row = button.closest('.tanggal-jadwal-row');
            row.remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.tanggal-jadwal-row');
            const removeButtons = document.querySelectorAll('.remove-btn');

            // Show remove buttons only if there's more than 1 row
            removeButtons.forEach(btn => {
                btn.classList.toggle('hidden', rows.length <= 1);
            });
        }

        // Initialize remove buttons visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });

      </script>
</x-layouts.app>

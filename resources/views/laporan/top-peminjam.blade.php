<x-layouts.app :title="__('Top Peminjam Aktif')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Top Peminjam Aktif</h1>
                <p class="text-gray-600 mt-1">Analisis pengguna paling aktif dalam peminjaman ruangan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('export.top-peminjam', request()->all()) }}" class="text-green-600 hover:text-green-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('laporan.top-peminjam') }}" class="ml-2 text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPeminjaman }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Peminjam Unik</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPeminjamUnik }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Jam</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalJam, 1) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Peminjams Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top 10 Peminjam</h3>
                <p class="text-sm text-gray-600 mt-1">Berdasarkan jumlah peminjaman (hanya yang disetujui)</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Peminjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Jam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan Unik</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($topPeminjams as $index => $peminjam)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($index === 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-800 font-bold text-sm">1</span>
                                    @elseif ($index === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 font-bold text-sm">2</span>
                                    @elseif ($index === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-800 font-bold text-sm">3</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-600 font-medium text-sm">{{ intval($index) + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $peminjam['nama_peminjam'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $peminjam['email_peminjam'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $peminjam['total_peminjaman'] }}</span>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($peminjam['total_peminjaman'] / max($topPeminjams->first()['total_peminjaman'], 1)) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($peminjam['total_jam'], 1) }} jam
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($peminjam['rata_rata_jam_per_peminjaman'], 1) }} jam
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $peminjam['unique_ruangans'] }} ruangan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data peminjaman dalam periode yang dipilih
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Insights -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Insights & Analisis</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Performa Peminjam</h4>
                    <div class="space-y-2">
                        @if ($topPeminjams->count() > 0)
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-sm text-green-800">
                                    <strong>{{ $topPeminjams->first()['nama_peminjam'] }}</strong> adalah peminjam teraktif dengan {{ $topPeminjams->first()['total_peminjaman'] }} peminjaman
                                </p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    Rata-rata {{ number_format($topPeminjams->avg('total_peminjaman'), 1) }} peminjaman per top peminjam
                                </p>
                            </div>
                        @else
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-800">Belum ada data peminjaman untuk dianalisis</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Utilisasi Ruangan</h4>
                    <div class="space-y-2">
                        @if ($totalPeminjamUnik > 0)
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-sm text-purple-800">
                                    Rata-rata {{ number_format($totalPeminjaman / $totalPeminjamUnik, 1) }} peminjaman per peminjam
                                </p>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-lg">
                                <p class="text-sm text-indigo-800">
                                    Total {{ number_format($totalJam / $totalPeminjaman, 1) }} jam per peminjam
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
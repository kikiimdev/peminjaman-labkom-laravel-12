<x-layouts.app :title="__('Pemeriksaan Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pemeriksaan Ruangan</h1>
                <p class="text-gray-600 mt-1">Analisis kondisi ruangan dan hasil pemeriksaan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('export.pemeriksaan', request()->all()) }}" class="text-green-600 hover:text-green-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <div>
                    <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select id="ruangan_id" name="ruangan_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kondisi</option>
                        <option value="BAIK" {{ request('kondisi') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                        <option value="BUTUH_PERBAIKAN" {{ request('kondisi') == 'BUTUH_PERBAIKAN' ? 'selected' : '' }}>Butuh Perbaikan</option>
                        <option value="RUSAK" {{ request('kondisi') == 'RUSAK' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('laporan.pemeriksaan-ruangan') }}" class="ml-2 text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-check text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pemeriksaan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pemeriksaan->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Kondisi Baik</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $kondisiCounts['BAIK'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tools text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Butuh Perbaikan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $kondisiCounts['BUTUH_PERBAIKAN'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rusak</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $kondisiCounts['RUSAK'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kondisi Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Kondisi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <canvas id="kondisiChart" width="400" height="400"></canvas>
                </div>
                <div class="space-y-3">
                    @foreach ($kondisiCounts as $kondisi => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $kondisi }}</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-{{ $kondisi == 'BAIK' ? 'green' : ($kondisi == 'BUTUH_PERBAIKAN' ? 'yellow' : 'red') }}-600 h-2 rounded-full"
                                         style="width: {{ $pemeriksaan->count() > 0 ? ($count / $pemeriksaan->count()) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pemeriksaan by Ruangan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pemeriksaan per Ruangan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Butuh Perbaikan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rusak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase Baik</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pemeriksaanByRuangan as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $data['ruangan']->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $data['total'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $data['baik'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $data['butuh_perbaikan'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $data['rusak'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format(($data['baik'] / $data['total']) * 100, 1) }}%</span>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($data['baik'] / $data['total']) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detailed Pemeriksaan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Pemeriksaan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pemeriksaan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->created_at->locale('id')->translatedFormat('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->ruangan->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->petugas->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($item->kondisi == 'BAIK') bg-green-100 text-green-800
                                        @elseif ($item->kondisi == 'BUTUH_PERBAIKAN') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $item->kondisi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($item->jadwal)
                                        {{ $item->jadwal->peminjam->name }}
                                    @else
                                        <span class="text-gray-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $item->catatan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Insights -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Insights & Rekomendasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Kondisi Ruangan</h4>
                    <div class="space-y-2">
                        @if ($pemeriksaan->count() > 0)
                            @php
                                $baikPercentage = ($kondisiCounts['BAIK'] ?? 0) / $pemeriksaan->count() * 100;
                                $perbaikanCount = $kondisiCounts['BUTUH_PERBAIKAN'] ?? 0;
                                $rusakCount = $kondisiCounts['RUSAK'] ?? 0;
                            @endphp
                            @if ($baikPercentage >= 80)
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <p class="text-sm text-green-800">✓ {{ number_format($baikPercentage, 1) }}% ruangan dalam kondisi baik</p>
                                </div>
                            @else
                                <div class="bg-yellow-50 p-3 rounded-lg">
                                    <p class="text-sm text-yellow-800">⚠ Hanya {{ number_format($baikPercentage, 1) }}% ruangan dalam kondisi baik</p>
                                </div>
                            @endif
                            @if ($perbaikanCount > 0)
                                <div class="bg-yellow-50 p-3 rounded-lg">
                                    <p class="text-sm text-yellow-800">• {{ $perbaikanCount }} ruangan butuh perbaikan</p>
                                </div>
                            @endif
                            @if ($rusakCount > 0)
                                <div class="bg-red-50 p-3 rounded-lg">
                                    <p class="text-sm text-red-800">• {{ $rusakCount }} ruangan dalam kondisi rusak</p>
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-800">Belum ada data pemeriksaan</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Rekomendasi</h4>
                    <div class="space-y-2">
                        @if ($pemeriksaan->count() > 0)
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">• Lakukan pemeriksaan rutin secara berkala</p>
                            </div>
                            @if ($rusakCount > 0)
                                <div class="bg-red-50 p-3 rounded-lg">
                                    <p class="text-sm text-red-800">• Prioritaskan perbaikan ruangan rusak</p>
                                </div>
                            @endif
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-sm text-purple-800">• Dokumentasikan setiap pemeriksaan dengan detail</p>
                            </div>
                        @else
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">• Mulai melakukan pemeriksaan ruangan rutin</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('kondisiChart').getContext('2d');
            const kondisiCounts = @json($kondisiCounts);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(kondisiCounts),
                    datasets: [{
                        data: Object.values(kondisiCounts),
                        backgroundColor: [
                            '#10B981', // Green for BAIK
                            '#F59E0B', // Yellow for BUTUH_PERBAIKAN
                            '#EF4444'  // Red for RUSAK
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.app>
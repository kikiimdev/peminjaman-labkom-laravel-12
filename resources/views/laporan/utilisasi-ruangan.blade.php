<x-layouts.app :title="__('Utilisasi Ruangan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Utilisasi Ruangan</h1>
                <p class="text-gray-600 mt-1">Analisis jam terpakai dan efisiensi penggunaan ruangan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('export.utilisasi-ruangan', request()->all()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('laporan.utilisasi-ruangan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select name="ruangan_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="md:col-span-3 flex items-end space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('laporan.utilisasi-ruangan') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Overall Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Total Jam Terpakai</h4>
                <p class="text-2xl font-bold text-indigo-600">{{ $totalJamTerpakai }}</p>
                <p class="text-xs text-gray-500">jam</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Kapasitas Total</h4>
                <p class="text-2xl font-bold text-gray-900">{{ $kapasitasTotal }}</p>
                <p class="text-xs text-gray-500">jam</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Persentase Utilisasi</h4>
                <p class="text-2xl font-bold text-green-600">{{ number_format($persentaseUtilisasi, 1) }}%</p>
                <p class="text-xs text-gray-500">efisiensi</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Ruangan Terpakai</h4>
                <p class="text-2xl font-bold text-blue-600">{{ $utilisasiByRuangan->count() }}</p>
                <p class="text-xs text-gray-500">dari {{ \App\Models\Ruangan::count() }} ruangan</p>
            </div>
        </div>

        <!-- Utilisasi per Ruangan -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Utilisasi per Ruangan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata/Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($utilisasiByRuangan as $ruangan => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $data['ruangan']->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $data['ruangan']->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data['total_jam'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data['total_hari'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($data['rata_rata_per_hari'], 1) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $totalHariOperasional * 9 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($data['total_jam'] / ($totalHariOperasional * 9)) * 100, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ number_format(($data['total_jam'] / ($totalHariOperasional * 9)) * 100, 1) }}%</span>
                                    </div>
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
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Performa Terbaik</h4>
                    @php
                        $bestPerforming = $utilisasiByRuangan->sortByDesc('total_jam')->first();
                    @endphp
                    @if ($bestPerforming)
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-green-900">{{ $bestPerforming['ruangan']->nama }}</p>
                            <p class="text-xs text-green-700">{{ $bestPerforming['total_jam'] }} jam terpakai</p>
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Perlu Perhatian</h4>
                    @php
                        $lowUtilization = $utilisasiByRuangan->filter(function($item) use ($totalHariOperasional) {
                            return ($item['total_jam'] / ($totalHariOperasional * 9)) * 100 < 30;
                        });
                    @endphp
                    @if ($lowUtilization->count() > 0)
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-yellow-900">{{ $lowUtilization->count() }} ruangan utilisasi rendah</p>
                            <p class="text-xs text-yellow-700">Kurang dari 30% kapasitas</p>
                        </div>
                    @else
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-green-900">Semua ruangan optimal</p>
                            <p class="text-xs text-green-700">Utilisasi di atas 30%</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
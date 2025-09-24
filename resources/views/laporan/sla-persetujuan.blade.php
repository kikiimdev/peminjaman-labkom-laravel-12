<x-layouts.app :title="__('SLA Persetujuan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">SLA Persetujuan</h1>
                <p class="text-gray-600 mt-1">Analisis waktu persetujuan dan kepatuhan terhadap SLA</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('export.sla-persetujuan', request()->all()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('laporan.sla-persetujuan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aktor</label>
                    <select name="aktor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Aktor</option>
                        @foreach ($aktors as $aktor)
                            <option value="{{ $aktor->id }}" {{ request('aktor_id') == $aktor->id ? 'selected' : '' }}>
                                {{ $aktor->name }} ({{ $aktor->role }})
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
                    <a href="{{ route('laporan.sla-persetujuan') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- SLA Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Total Persetujuan</h4>
                <p class="text-2xl font-bold text-gray-900">{{ $totalPersetujuan }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Memenuhi SLA</h4>
                <p class="text-2xl font-bold text-green-600">{{ $memenuhiSla }}</p>
                <p class="text-xs text-gray-500">{{ $slaHours }} jam target</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Tidak Memenuhi SLA</h4>
                <p class="text-2xl font-bold text-red-600">{{ $tidakMemenuhiSla }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Compliance Rate</h4>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($persentaseSla, 1) }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $persentaseSla }}%"></div>
                </div>
            </div>
        </div>

        <!-- SLA Performance Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">SLA Performance Overview</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Memenuhi SLA</span>
                        <span class="text-sm font-medium text-green-600">{{ $memenuhiSla }} ({{ round(($memenuhiSla / $totalPersetujuan) * 100, 1) }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ ($memenuhiSla / $totalPersetujuan) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Tidak Memenuhi SLA</span>
                        <span class="text-sm font-medium text-red-600">{{ $tidakMemenuhiSla }} ({{ round(($tidakMemenuhiSla / $totalPersetujuan) * 100, 1) }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-red-600 h-3 rounded-full" style="width: {{ ($tidakMemenuhiSla / $totalPersetujuan) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLA Details Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Detail SLA Persetujuan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Peminjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Persetujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih (Jam)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SLA Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($slaResults as $result)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $result['jadwal']->id }}</div>
                                    <div class="text-xs text-gray-500">{{ $result['jadwal']->ruangan->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $result['jadwal']->peminjam->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $result['persetujuan']->aktor->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $result['persetujuan']->aktor->role }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result['waktu_pengajuan']->locale('id')->translatedFormat('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result['waktu_persetujuan']->locale('id')->translatedFormat('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($result['memenuhi_sla']) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $result['selisih_jam'] }} jam
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($result['memenuhi_sla']) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $result['memenuhi_sla'] ? 'Memenuhi' : 'Tidak Memenuhi' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('jadwal.show', $result['jadwal']) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </a>
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
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Performance Analysis</h4>
                    <div class="space-y-2">
                        @if ($persentaseSla >= 90)
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-sm text-green-800">✓ SLA compliance sangat baik ({{ $persentaseSla }}%)</p>
                            </div>
                        @elseif ($persentaseSla >= 70)
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <p class="text-sm text-yellow-800">⚠ SLA compliance cukup baik, masih bisa ditingkatkan</p>
                            </div>
                        @else
                            <div class="bg-red-50 p-3 rounded-lg">
                                <p class="text-sm text-red-800">✗ SLA compliance perlu perhatian serius</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Rekomendasi</h4>
                    <div class="space-y-2">
                        @if ($tidakMemenuhiSla > 0)
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">• Percepat proses persetujuan untuk {{ $tidakMemenuhiSla }} pengajuan</p>
                            </div>
                        @endif
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-800">• Target SLA saat ini: {{ $slaHours }} jam</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-800">• Rata-rata waktu persetujuan perlu dipantau</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
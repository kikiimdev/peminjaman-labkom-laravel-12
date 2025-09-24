<x-layouts.app :title="__('Riwayat Perubahan Status')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Riwayat Perubahan Status</h1>
                <p class="text-gray-600 mt-1">Analisis perubahan status jadwal peminjaman ruangan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('export.riwayat-status', request()->all()) }}" class="text-green-600 hover:text-green-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label for="aktor_id" class="block text-sm font-medium text-gray-700 mb-1">Aktor</label>
                    <select id="aktor_id" name="aktor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Aktor</option>
                        @foreach ($aktors as $aktor)
                            <option value="{{ $aktor->id }}" {{ request('aktor_id') == $aktor->id ? 'selected' : '' }}>
                                {{ $aktor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="dari" class="block text-sm font-medium text-gray-700 mb-1">Status Dari</label>
                    <select id="dari" name="dari" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU" {{ request('dari') == 'MENUNGGU' ? 'selected' : '' }}>Menunggu</option>
                        <option value="DISETUJUI" {{ request('dari') == 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
                        <option value="DITOLAK" {{ request('dari') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                        <option value="DIBATALKAN" {{ request('dari') == 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="SELESAI" {{ request('dari') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label for="menjadi" class="block text-sm font-medium text-gray-700 mb-1">Status Menjadi</label>
                    <select id="menjadi" name="menjadi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU" {{ request('menjadi') == 'MENUNGGU' ? 'selected' : '' }}>Menunggu</option>
                        <option value="DISETUJUI" {{ request('menjadi') == 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
                        <option value="DITOLAK" {{ request('menjadi') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                        <option value="DIBATALKAN" {{ request('menjadi') == 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="SELESAI" {{ request('menjadi') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('laporan.riwayat-status') }}" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-history text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Perubahan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $riwayatStatus->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Jumlah Aktor</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $riwayatStatus->pluck('aktor_id')->unique()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-day text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Perubahan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $riwayatStatus->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Changes Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Perubahan Status</h3>
            <div class="space-y-3">
                @foreach ($statusChanges as $change => $count)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">{{ $change }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Data Riwayat Perubahan Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Dari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Menjadi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($riwayatStatus as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jadwal->keperluan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jadwal->ruangan->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jadwal->peminjam->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->aktor->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $item->dari }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if ($item->menjadi == 'DISETUJUI') bg-green-100 text-green-800
                                        @elseif ($item->menjadi == 'DITOLAK') bg-red-100 text-red-800
                                        @elseif ($item->menjadi == 'SELESAI') bg-blue-100 text-blue-800
                                        @elseif ($item->menjadi == 'DIBATALKAN') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $item->menjadi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->catatan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
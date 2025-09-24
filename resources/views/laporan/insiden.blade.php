<x-layouts.app :title="__('Laporan Insiden')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Insiden</h1>
                <p class="text-gray-600 mt-1">Analisis insiden per ruangan dan periode waktu</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('export.insiden', request()->all()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('laporan.insiden') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                    <select name="tingkat" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Tingkat</option>
                        <option value="RENDAH" {{ request('tingkat') == 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                        <option value="SEDANG" {{ request('tingkat') == 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                        <option value="TINGGI" {{ request('tingkat') == 'TINGGI' ? 'selected' : '' }}>Tinggi</option>
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
                <div class="md:col-span-2 flex items-end space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('laporan.insiden') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Total Insiden</h4>
                <p class="text-2xl font-bold text-gray-900">{{ $insidens->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Insiden Aktif</h4>
                <p class="text-2xl font-bold text-red-600">{{ $insidens->where('selesai_pada', null)->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Insiden Selesai</h4>
                <p class="text-2xl font-bold text-green-600">{{ $insidens->whereNotNull('selesai_pada')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-medium text-gray-600 mb-1">Rata-rata Penyelesaian</h4>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($rataRataPenyelesaian, 1) }}</p>
                <p class="text-xs text-gray-500">jam</p>
            </div>
        </div>

        <!-- Tingkat Insiden -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Distribusi Tingkat Insiden</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($tingkatCounts as $tingkat => $count)
                        <div class="text-center p-4 rounded-lg
                            @if ($tingkat == 'TINGGI') bg-red-50 border border-red-200
                            @elseif ($tingkat == 'SEDANG') bg-yellow-50 border border-yellow-200
                            @else bg-green-50 border border-green-200 @endif">
                            <div class="text-3xl font-bold mb-2
                                @if ($tingkat == 'TINGGI') text-red-600
                                @elseif ($tingkat == 'SEDANG') text-yellow-600
                                @else text-green-600 @endif">
                                {{ $count }}
                            </div>
                            <div class="text-sm font-medium
                                @if ($tingkat == 'TINGGI') text-red-900
                                @elseif ($tingkat == 'SEDANG') text-yellow-900
                                @else text-green-900 @endif">
                                {{ $tingkat }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ round(($count / $insidens->count()) * 100, 1) }}% dari total
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Insiden per Ruangan -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Insiden per Ruangan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rendah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tinggi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktif</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($insidensByRuangan as $ruangan => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $data['ruangan']->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $data['ruangan']->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data['total'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">{{ $data['rendah'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $data['sedang'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $data['tinggi'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">{{ $data['terselesaikan'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $data['belum_terselesaikan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Insiden -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Insiden Terkini</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach ($insidens->sortByDesc('created_at')->take(5) as $insiden)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($insiden->isLowSeverity()) bg-green-100 text-green-800
                                        @elseif ($insiden->isMediumSeverity()) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $insiden->getTingkatLabel() }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $insiden->ruangan->nama }}</span>
                                    <span class="text-sm text-gray-500">{{ $insiden->created_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($insiden->deskripsi, 100) }}</p>
                            </div>
                            <a href="{{ route('insiden.show', $insiden) }}" class="text-indigo-600 hover:text-indigo-800 text-sm ml-4">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
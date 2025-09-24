<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-calendar"></i>
                <span>{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Ruangan -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Lab</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ruangans'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-door-open text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Jadwal -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Jadwal</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_jadwals'] }}</p>
                        <p class="text-xs text-gray-500">
                            Bulan ini: {{ $stats['jadwals_this_month'] }}
                            @if($stats['jadwals_last_month'] > 0)
                                <span class="{{ $stats['jadwals_this_month'] >= $stats['jadwals_last_month'] ? 'text-green-600' : 'text-red-600' }}">
                                    ({{ $stats['jadwals_this_month'] >= $stats['jadwals_last_month'] ? '+' : '' }}{{ $stats['jadwals_this_month'] - $stats['jadwals_last_month'] }})
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Fasilitas -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Fasilitas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_fasilitas'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-list-ul text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Pengguna (Admin only) / My Incidents (User only) -->
            @if(Auth::user()->role === 'ADMIN')
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pengguna</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-users text-yellow-600"></i>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Insiden Saya</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $facilitySummary['total_insidens'] }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $facilitySummary['open_insidens'] }} aktif
                        </p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Jadwal Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Jadwal</h3>
                <div class="space-y-3">
                    @foreach ($jadwalStatusCounts as $status => $data)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $status }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $stats['total_jadwals'] > 0 ? ($data->total / $stats['total_jadwals']) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $data->total }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Room Utilization Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Utilisasi Lab (30 Hari Terakhir)</h3>
                <div class="space-y-3 max-h-48 overflow-y-auto">
                    @foreach ($roomStatusSummary as $room)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $room['nama'] }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($room['total_jam_30_hari'] / 100) * 100, 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ round($room['total_jam_30_hari'], 1) }}j</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Today's Schedule & Pending Approvals -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Today's Schedule -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Jadwal Hari Ini</h3>
                    <span class="text-sm text-gray-500">{{ $todaySchedules->count() }} jadwal</span>
                </div>
                @if ($todaySchedules->count() > 0)
                    <div class="space-y-3">
                        @foreach ($todaySchedules as $schedule)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $schedule->jadwal->keperluan }}</p>
                                    <p class="text-sm text-gray-600">{{ $schedule->jadwal->ruangan->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $schedule->jam_mulai }} - {{ $schedule->jam_berakhir }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $schedule->jadwal->peminjam->name }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Tidak ada jadwal hari ini</p>
                @endif
            </div>

            <!-- Pending Approvals (Admin only) / My Recent Incidents (User only) -->
            @if(Auth::user()->role === 'ADMIN')
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Menunggu Persetujuan</h3>
                    <span class="text-sm text-gray-500">{{ $pendingApprovals->count() }} pending</span>
                </div>
                @if ($pendingApprovals->count() > 0)
                    <div class="space-y-3">
                        @foreach ($pendingApprovals as $approval)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $approval->keperluan }}</p>
                                    <p class="text-sm text-gray-600">{{ $approval->ruangan->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $approval->peminjam->name }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('persetujuan.approve', $approval->id) }}" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="{{ route('persetujuan.reject', $approval->id) }}" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Tidak ada persetujuan pending</p>
                @endif
            </div>
            @else
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Insiden Terbaru</h3>
                    <span class="text-sm text-gray-500">{{ $recentIncidents->count() }} insiden</span>
                </div>
                @if ($recentIncidents->count() > 0)
                    <div class="space-y-3">
                        @foreach ($recentIncidents as $incident)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $incident->tingkat }}</p>
                                    <p class="text-sm text-gray-600">{{ $incident->ruangan->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $incident->created_at->locale('id')->translatedFormat('d M Y') }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($incident->isOpen()) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $incident->isOpen() ? 'Open' : 'Closed' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Tidak ada insiden</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Admin Only Sections -->
        @if(Auth::user()->role === 'ADMIN')
        <!-- Monthly Trends -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Bulanan (6 Bulan Terakhir)</h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                @foreach ($monthlyTrends as $trend)
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">{{ $trend['month'] }}</p>
                        <div class="space-y-2">
                            <div class="bg-blue-100 p-2 rounded">
                                <p class="text-xs text-blue-800">Jadwal</p>
                                <p class="font-bold text-blue-900">{{ $trend['total_jadwals'] }}</p>
                            </div>
                            <div class="bg-green-100 p-2 rounded">
                                <p class="text-xs text-green-800">Disetujui</p>
                                <p class="font-bold text-green-900">{{ $trend['approved_jadwals'] }}</p>
                            </div>
                            <div class="bg-red-100 p-2 rounded">
                                <p class="text-xs text-red-800">Insiden</p>
                                <p class="font-bold text-red-900">{{ $trend['total_insidens'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Upcoming Maintenance -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pemeliharaan Terjadwal</h3>
                <span class="text-sm text-gray-500">{{ $upcomingMaintenance->count() }} terjadwal</span>
            </div>
            @if ($upcomingMaintenance->count() > 0)
                <div class="space-y-3">
                    @foreach ($upcomingMaintenance as $maintenance)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $maintenance->ruangan->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $maintenance->jenis }}</p>
                                <p class="text-xs text-gray-500">{{ $maintenance->dijadwalkan_pada->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $maintenance->getStatusLabel() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Tidak ada pemeliharaan terjadwal</p>
            @endif
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @can('create', App\Models\Jadwal::class)
                    <a href="{{ route('jadwal.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-3 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Ajukan Jadwal
                    </a>
                @endcan

                @can('create', App\Models\Insiden::class)
                    <a href="{{ route('insiden.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-center px-4 py-3 rounded-lg font-medium">
                        <i class="fas fa-exclamation mr-2"></i>Lapor Insiden
                    </a>
                @endcan

                @if(Auth::user()->role === 'ADMIN')
                    <a href="{{ route('laporan.index') }}" class="bg-green-600 hover:bg-green-700 text-white text-center px-4 py-3 rounded-lg font-medium">
                        <i class="fas fa-chart-bar mr-2"></i>Laporan
                    </a>
                @else
                    <a href="{{ route('jadwal.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-3 rounded-lg font-medium">
                        <i class="fas fa-calendar mr-2"></i>Jadwal Saya
                    </a>

                    <a href="{{ route('insiden.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-3 rounded-lg font-medium">
                        <i class="fas fa-history mr-2"></i>Riwayat Insiden
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
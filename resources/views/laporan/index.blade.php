<x-layouts.app :title="__('Laporan')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan & Analitik</h1>
                <p class="text-gray-600 mt-1">Pantau kinerja dan analisis penggunaan ruangan laboratorium</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Peminjaman -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-indigo-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Jadwal::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Ruangan Aktif -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-door-open text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ruangan Aktif</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Ruangan::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Insiden Aktif -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Insiden Aktif</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Insiden::open()->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Pemeliharaan Records -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tools text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pemeliharaan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\PemeliharaanRuangan::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Peminjaman Reports -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chart-line text-indigo-600 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Peminjaman</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Analisis data peminjaman ruangan dan statistik penggunaan</p>
                    <div class="space-y-2">
                        <a href="{{ route('laporan.rekap-peminjaman') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-file-alt mr-2"></i>Rekap Peminjaman per Ruangan & Periode
                        </a>
                        <a href="{{ route('laporan.utilisasi-ruangan') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-clock mr-2"></i>Utilisasi Ruangan (Jam Terpakai)
                        </a>
                        <a href="{{ route('laporan.status-peminjaman') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-tasks mr-2"></i>Status Peminjaman
                        </a>
                        <a href="{{ route('laporan.top-peminjam') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-users mr-2"></i>Top Peminjam Aktif
                        </a>
                        <a href="{{ route('export.rekap-peminjaman') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export Data
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kualitas Reports -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-clipboard-check text-green-600 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Kualitas</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Pemeriksaan kondisi ruangan dan insiden yang terjadi</p>
                    <div class="space-y-2">
                        <a href="{{ route('laporan.pemeriksaan-ruangan') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-search mr-2"></i>Pemeriksaan Ruangan (IN/OUT & Kondisi)
                        </a>
                        <a href="{{ route('laporan.insiden') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-exclamation-circle mr-2"></i>Insiden per Ruangan/Periode
                        </a>
                        <a href="{{ route('laporan.pemeliharaan-ruangan') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-wrench mr-2"></i>Pemeliharaan Ruangan
                        </a>
                        <a href="{{ route('export.pemeriksaan') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export Pemeriksaan
                        </a>
                        <a href="{{ route('export.insiden') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export Insiden
                        </a>
                    </div>
                </div>
            </div>

            <!-- Proses Reports -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-cogs text-yellow-600 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Proses</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Analisis proses persetujuan dan perubahan status</p>
                    <div class="space-y-2">
                        <a href="{{ route('laporan.sla-persetujuan') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-stopwatch mr-2"></i>SLA Persetujuan
                        </a>
                        <a href="{{ route('laporan.riwayat-status') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-history mr-2"></i>Riwayat Perubahan Status
                        </a>
                        <a href="{{ route('export.sla-persetujuan') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export SLA
                        </a>
                        <a href="{{ route('export.riwayat-status') }}" class="block text-green-600 hover:text-green-800 text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Data Export -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Master Data</h3>
            <p class="text-gray-600 mb-4">Download data master untuk keperluan administrasi</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('export.ruangan') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-building mr-2"></i>Data Ruangan
                </a>
                <a href="{{ route('export.fasilitas') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-list-ul mr-2"></i>Data Fasilitas
                </a>
                <a href="{{ route('export.rekap-peminjaman') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-calendar mr-2"></i>Data Jadwal
                </a>
                <a href="{{ route('export.pemeliharaan') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-tools mr-2"></i>Data Pemeliharaan
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terkini</h3>
            <div class="space-y-4">
                <!-- Recent Insiden -->
                @foreach (\App\Models\Insiden::latest()->take(3)->get() as $insiden)
                    <div class="flex items-center space-x-3 p-3 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Insiden {{ $insiden->getTingkatLabel() }} - {{ $insiden->ruangan->nama }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $insiden->created_at->locale('id')->translatedFormat('d M Y H:i') }}</p>
                        </div>
                        <a href="{{ route('insiden.show', $insiden) }}" class="text-red-600 hover:text-red-800 text-sm">
                            Detail
                        </a>
                    </div>
                @endforeach

                <!-- Recent Maintenance -->
                @foreach (\App\Models\PemeliharaanRuangan::latest()->take(2)->get() as $maintenance)
                    <div class="flex items-center space-x-3 p-3 bg-yellow-50 rounded-lg">
                        <i class="fas fa-tools text-yellow-600"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Pemeliharaan - {{ $maintenance->ruangan->nama }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $maintenance->created_at->locale('id')->translatedFormat('d M Y H:i') }}</p>
                        </div>
                        <a href="{{ route('pemeliharaan.show', $maintenance) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">
                            Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>

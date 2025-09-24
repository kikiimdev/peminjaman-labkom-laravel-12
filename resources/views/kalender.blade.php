<x-layouts.app :title="__('Kalender Peminjaman')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kalender Peminjaman</h1>
                <p class="text-gray-600 mt-2">
                    @if(Auth::user()->role === 'ADMIN')
                        Jadwal peminjaman ruangan mendatang (Semua Pengguna)
                    @else
                        Jadwal peminjaman saya mendatang
                    @endif
                </p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('kalender') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU" {{ request('status') == 'MENUNGGU' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="DISETUJUI" {{ request('status') == 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
                        <option value="DITOLAK" {{ request('status') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                        <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">Filter Ruangan</label>
                    <select
                        id="ruangan_id"
                        name="ruangan_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Filter Bulan</label>
                    <input
                        type="month"
                        id="month"
                        name="month"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ request('month') }}"
                        min="{{ date('Y-m') }}">
                </div>
                <div class="md:col-span-3 flex items-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-filter mr-2"></i>Terapkan Filter
                    </button>
                    <a href="{{ route('kalender') }}" class="ml-2 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Calendar -->
        <div class="bg-white rounded-lg shadow p-6">
            @if (count($eventsByDate) > 0)
                <div class="space-y-4">
                    @php
                        $currentMonth = null;
                    @endphp
                    @foreach ($eventsByDate as $date => $events)
                        @php
                            $eventDate = \Carbon\Carbon::parse($date);
                            $monthName = $eventDate->locale('id')->translatedFormat('F Y');
                        @endphp

                        @if ($monthName !== $currentMonth)
                            @if ($currentMonth !== null)
                                </div>
                            @endif
                            <div class="month-section">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">
                                    {{ $monthName }}
                                </h3>
                                <div class="space-y-3">
                            @php
                                $currentMonth = $monthName;
                            @endphp
                        @endif

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">
                                    {{ $eventDate->locale('id')->translatedFormat('l, d F Y') }}
                                </h4>
                                <span class="text-sm text-gray-500">
                                    {{ count($events) }} peminjaman
                                </span>
                            </div>

                            <div class="space-y-2">
                                @foreach ($events as $event)
                                    @php
                                        $statusColors = [
                                            'MENUNGGU' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'clock'],
                                            'DISETUJUI' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle'],
                                            'DITOLAK' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'times-circle'],
                                            'SELESAI' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'check-double']
                                        ];
                                        $currentStatus = $event['jadwal']->status;
                                        $color = $statusColors[$currentStatus] ?? $statusColors['MENUNGGU'];
                                    @endphp
                                    <a href="{{ route('jadwal.show', $event['jadwal']->id) }}" class="block bg-gray-50 rounded-lg p-3 hover:bg-gray-100 hover:shadow-md transition-all cursor-pointer border border-transparent hover:border-indigo-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $color['bg'] }} {{ $color['text'] }}">
                                                        <i class="fas fa-{{ $color['icon'] }} mr-1"></i>{{ $currentStatus }}
                                                    </span>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $event['jadwal']->ruangan->nama }}
                                                    </span>
                                                    @if ($event['tanggal']->jam_mulai && $event['tanggal']->jam_berakhir)
                                                        <span class="text-sm text-gray-600">
                                                            {{ $event['tanggal']->jam_mulai }} - {{ $event['tanggal']->jam_berakhir }}
                                                        </span>
                                                    @else
                                                        <span class="text-sm text-indigo-600">
                                                            <i class="fas fa-calendar-day mr-1"></i>Full Day
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $event['jadwal']->keperluan }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Peminjam: {{ $event['jadwal']->peminjam->name }}
                                                </p>
                                            </div>
                                            <div class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-arrow-right"></i>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if ($currentMonth !== null)
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal</h3>
                    <p class="text-gray-500">
                        @if(request()->filled('ruangan_id') || request()->filled('month'))
                            Tidak ada jadwal peminjaman yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada jadwal peminjaman yang disetujui.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($jadwals->hasPages())
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $jadwals->firstItem() }}-{{ $jadwals->lastItem() }} dari {{ $jadwals->total() }} jadwal
                    </div>
                    {{ $jadwals->links() }}
                </div>
            </div>
        @endif

        <!-- Informasi Status -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg shadow-sm border border-indigo-100 p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Status</h3>
                    <p class="text-sm text-gray-600">Panduan untuk memahami status dan tampilan jadwal</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Status Jadwal -->
                <div class="space-y-3">
                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide">Status Jadwal</h4>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>MENUNGGU
                            </span>
                            <span class="text-sm text-gray-700">Menunggu persetujuan admin</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>DISETUJUI
                            </span>
                            <span class="text-sm text-gray-700">Jadwal telah disetujui</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>DITOLAK
                            </span>
                            <span class="text-sm text-gray-700">Jadwal tidak disetujui</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check-double mr-1"></i>SELESAI
                            </span>
                            <span class="text-sm text-gray-700">Jadwal telah selesai</span>
                        </div>
                    </div>
                </div>

                <!-- Waktu Peminjaman -->
                <div class="space-y-3">
                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide">Waktu Peminjaman</h4>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <span class="text-sm text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full font-medium">
                                <i class="fas fa-calendar-day mr-1"></i>Full Day
                            </span>
                            <span class="text-sm text-gray-700">Peminjaman sehari penuh</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <span class="text-sm text-blue-600 bg-blue-100 px-3 py-1 rounded font-medium">
                                <i class="fas fa-clock mr-1"></i>14:00 - 16:00
                            </span>
                            <span class="text-sm text-gray-700">Waktu spesifik</span>
                        </div>
                    </div>
                </div>

                <!-- Navigasi -->
                <div class="space-y-3">
                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide">Navigasi</h4>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <div class="text-indigo-600">
                                <i class="fas fa-arrow-right text-lg"></i>
                            </div>
                            <span class="text-sm text-gray-700">Klik untuk lihat detail jadwal</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/50 transition-colors">
                            <div class="text-green-600">
                                <i class="fas fa-filter text-lg"></i>
                            </div>
                            <span class="text-sm text-gray-700">Gunakan filter untuk pencarian spesifik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
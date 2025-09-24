@extends('layouts.calendar')

@section('title', 'Kalender Peminjaman Ruangan')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kalender Peminjaman Ruangan</h1>
            <p class="text-gray-600">Jadwal peminjaman ruangan yang telah disetujui</p>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('calendar.page') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-door-open mr-1"></i>Filter Ruangan
                    </label>
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
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>Filter Bulan
                    </label>
                    <input
                        type="month"
                        id="month"
                        name="month"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ request('month') }}"
                        min="{{ date('Y-m') }}">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-filter mr-2"></i>Terapkan Filter
                    </button>
                    <a href="{{ route('calendar.page') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="text-3xl font-bold text-indigo-600">{{ count($eventsByDate) }}</div>
                <div class="text-sm text-gray-600">Hari Aktif</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="text-3xl font-bold text-green-600">
                    {{ collect($eventsByDate)->sum(function($events) { return count($events); }) }}
                </div>
                <div class="text-sm text-gray-600">Total Peminjaman</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="text-3xl font-bold text-blue-600">{{ count($ruangans) }}</div>
                <div class="text-sm text-gray-600">Ruangan Tersedia</div>
            </div>
        </div>

        <!-- Calendar Events -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @if (count($eventsByDate) > 0)
                <div class="space-y-6">
                    @php
                        $currentMonth = null;
                    @endphp
                    @foreach ($eventsByDate as $date => $events)
                        @php
                            $eventDate = \Carbon\Carbon::parse($date);
                            $monthName = $eventDate->locale('id')->translatedFormat('F Y');
                            $isToday = $eventDate->isToday();
                            $isPast = $eventDate->isPast();
                        @endphp

                        @if ($monthName !== $currentMonth)
                            @if ($currentMonth !== null)
                                </div>
                            @endif
                            <div class="month-section">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-200">
                                    <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>
                                    {{ $monthName }}
                                </h3>
                                <div class="space-y-4">
                            @php
                                $currentMonth = $monthName;
                            @endphp
                        @endif

                        <!-- Date Card -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow {{ $isToday ? 'ring-2 ring-indigo-500 bg-indigo-50' : '' }} {{ $isPast ? 'opacity-75' : '' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold {{ $isToday ? 'text-indigo-600' : 'text-gray-900' }}">
                                            {{ $eventDate->format('d') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $eventDate->locale('id')->translatedFormat('D') }}
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">
                                            {{ $eventDate->locale('id')->translatedFormat('l, d F Y') }}
                                        </h4>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-sm text-gray-600">
                                                {{ count($events) }} peminjaman
                                            </span>
                                            @if ($isToday)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Hari Ini
                                                </span>
                                            @endif
                                            @if ($isPast && !$isToday)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Events List -->
                            <div class="space-y-2">
                                @foreach ($events as $event)
                                    <a href="{{ route('jadwal.show', $event['jadwal']->id) }}" class="block bg-gray-50 rounded-lg p-4 hover:bg-gray-100 hover:shadow-md transition-all cursor-pointer border-l-4 border-green-500">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>DISETUJUI
                                                    </span>
                                                    <span class="font-medium text-gray-900">
                                                        <i class="fas fa-door-closed mr-1 text-blue-600"></i>
                                                        {{ $event['jadwal']->ruangan->nama }}
                                                    </span>
                                                    @if ($event['tanggal']->jam_mulai && $event['tanggal']->jam_berakhir)
                                                        <span class="text-sm text-gray-600 bg-blue-100 px-2 py-1 rounded">
                                                            <i class="fas fa-clock mr-1"></i>{{ $event['tanggal']->jam_mulai }} - {{ $event['tanggal']->jam_berakhir }}
                                                        </span>
                                                    @else
                                                        <span class="text-sm text-indigo-600 bg-indigo-100 px-2 py-1 rounded">
                                                            <i class="fas fa-calendar-day mr-1"></i>Full Day
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-700 mb-2">
                                                    <i class="fas fa-clipboard-list mr-1 text-gray-500"></i>
                                                    {{ $event['jadwal']->keperluan }}
                                                </p>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-user mr-1"></i>
                                                    <span>{{ $event['jadwal']->peminjam->name }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-users mr-1"></i>
                                                    <span>{{ $event['jadwal']->peminjam->role }}</span>
                                                </div>
                                            </div>
                                            <div class="text-indigo-600 hover:text-indigo-800 ml-4">
                                                <i class="fas fa-arrow-right text-lg"></i>
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
                <div class="text-center py-16">
                    <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada Jadwal</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        @if(request()->filled('ruangan_id') || request()->filled('month'))
                            Tidak ada jadwal peminjaman yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada jadwal peminjaman yang disetujui untuk periode ini.
                        @endif
                    </p>
                    @if(request()->filled('ruangan_id') || request()->filled('month'))
                        <a href="{{ route('calendar.page') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-refresh mr-2"></i>Lihat Semua Jadwal
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($jadwals->hasPages())
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $jadwals->firstItem() }}-{{ $jadwals->lastItem() }} dari {{ $jadwals->total() }} jadwal
                    </div>
                    {{ $jadwals->links() }}
                </div>
            </div>
        @endif

        <!-- Legend Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informasi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        DISETUJUI
                    </span>
                    <span class="text-sm text-gray-600">Status disetujui</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-indigo-600 bg-indigo-100 px-2 py-1 rounded">
                        <i class="fas fa-calendar-day mr-1"></i>Full Day
                    </span>
                    <span class="text-sm text-gray-600">Peminjaman sehari penuh</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 bg-blue-100 px-2 py-1 rounded">
                        <i class="fas fa-clock mr-1"></i>14:00 - 16:00
                    </span>
                    <span class="text-sm text-gray-600">Waktu spesifik</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Hari Ini
                    </span>
                    <span class="text-sm text-gray-600">Peminjaman hari ini</span>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center text-sm text-gray-500">
            <p>
                <i class="fas fa-sync-alt mr-1"></i>
                Data diperbarui secara otomatis. Hubungi administrator untuk informasi lebih lanjut.
            </p>
        </div>
    </div>
@endsection
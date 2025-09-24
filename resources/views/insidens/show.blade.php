<x-layouts.app :title="__('Detail Insiden')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Insiden</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap laporan insiden</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('insiden.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                @if ($insiden->jadwal_id)
                    <a href="{{ route('jadwal.show', $insiden->jadwal) }}" class="text-indigo-600 hover:text-indigo-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-calendar mr-2"></i>Lihat Jadwal
                    </a>
                @endif
                @can('update', $insiden)
                    @if ($insiden->isOpen())
                        <a href="{{ route('insiden.edit', $insiden) }}" class="text-yellow-600 hover:text-yellow-800 px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                @endcan
            </div>
        </div>

        <!-- Status Badge -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Insiden #{{ $insiden->id }}</h2>
                    <p class="text-gray-600 mt-1">{{ $insiden->ruangan->nama }} - {{ $insiden->ruangan->lokasi }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if ($insiden->isLowSeverity()) bg-green-100 text-green-800
                        @elseif ($insiden->isMediumSeverity()) bg-yellow-100 text-yellow-800
                        @elseif ($insiden->isHighSeverity()) bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $insiden->getTingkatLabel() }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if ($insiden->isOpen()) bg-red-100 text-red-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $insiden->isOpen() ? 'Open' : 'Closed' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @can('update', $insiden)
            @if ($insiden->isOpen())
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-wrap gap-3">
                        <!-- Penyelesaian -->
                        <form method="POST" action="{{ route('insidens.mark-as-completed', $insiden) }}" onsubmit="return confirm('Apakah Anda yakin insiden ini sudah selesai?')">
                            @csrf
                            <div class="flex items-center space-x-2">
                                <input type="text"
                                       name="ditangani_oleh"
                                       placeholder="Nama penanggung jawab"
                                       required
                                       class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       value="{{ $insiden->ditangani_oleh ?? '' }}">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                                    <i class="fas fa-check mr-2"></i>Tandai Selesai
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endcan

        <!-- Informasi Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Utama -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kejadian</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Kejadian</span>
                        <span class="font-medium">{{ $insiden->created_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ruangan</span>
                        <span class="font-medium">
                            <a href="{{ route('ruangan.show', $insiden->ruangan) }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $insiden->ruangan->nama }}
                            </a>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tingkat Insiden</span>
                        <span class="font-medium">{{ $insiden->getTingkatLabel() }}</span>
                    </div>
                    @if ($insiden->jadwal_id)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Terkait Jadwal</span>
                            <span class="font-medium">
                                <a href="{{ route('jadwal.show', $insiden->jadwal) }}" class="text-indigo-600 hover:text-indigo-800">
                                    {{ $insiden->jadwal->kode_peminjaman }}
                                </a>
                            </span>
                        </div>
                    @endif
                    @if ($insiden->getDuration())
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi Penyelesaian</span>
                            <span class="font-medium">{{ $insiden->getDuration() }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Penanganan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penanganan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pelapor</span>
                        <span class="font-medium">{{ $insiden->pelapor->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ditangani Oleh</span>
                        <span class="font-medium">{{ $insiden->ditangani_oleh ?? 'Belum ditugaskan' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="font-medium">{{ $insiden->isOpen() ? 'Open' : 'Closed' }}</span>
                    </div>
                    @if ($insiden->selesai_pada)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Selesai Pada</span>
                            <span class="font-medium">{{ $insiden->selesai_pada->locale('id')->translatedFormat('d F Y H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diperbarui Pada</span>
                        <span class="font-medium">{{ $insiden->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi Kejadian</h3>
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $insiden->deskripsi }}</p>
            </div>
        </div>

      </div>
</x-layouts.app>

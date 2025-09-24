<x-layouts.app :title="__('Detail Jadwal')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('jadwal.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Peminjaman</h1>
            </div>
            <div class="flex space-x-2">
                @can('update', $jadwal)
                    @if ($jadwal->status == 'MENUNGGU' || Auth::user()->role == 'ADMIN')
                        <a href="{{ route('jadwal.edit', $jadwal) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                @endcan
                @can('delete', $jadwal)
                    @if (in_array($jadwal->status, ['MENUNGGU', 'DITOLAK']))
                        <form method="POST" action="{{ route('jadwal.destroy', $jadwal) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Status Peminjaman</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if ($jadwal->status == 'DISETUJUI') bg-green-100 text-green-800
                    @elseif ($jadwal->status == 'MENUNGGU') bg-yellow-100 text-yellow-800
                    @elseif ($jadwal->status == 'DITOLAK') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $jadwal->status }}
                </span>
            </div>

            <!-- Approval Actions -->
            @if ($jadwal->status == 'MENUNGGU')
                @can('approve', $jadwal)
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Aksi Persetujuan</h3>
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('jadwals.approve', $jadwal) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                                    <i class="fas fa-check mr-2"></i>Setujui
                                </button>
                            </form>
                            <button type="button" onclick="document.getElementById('rejectForm').classList.remove('hidden')" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="fas fa-times mr-2"></i>Tolak
                            </button>
                        </div>
                    </div>
                @endcan
            @endif

            <!-- Reject Form (Hidden by default) -->
            @if ($jadwal->status == 'MENUNGGU')
                @can('reject', $jadwal)
                    <form id="rejectForm" method="POST" action="{{ route('jadwals.reject', $jadwal) }}" class="hidden border-t pt-4">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                                <textarea name="catatan" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                    <i class="fas fa-times mr-2"></i>Kirim Penolakan
                                </button>
                                <button type="button" onclick="document.getElementById('rejectForm').classList.add('hidden')" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </form>
                @endcan
            @endif
        </div>

        <!-- Main Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjaman</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Keperluan</p>
                            <p class="font-medium text-gray-900">{{ $jadwal->keperluan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Peminjam</p>
                            <p class="font-medium text-gray-900">{{ $jadwal->peminjam->name }}</p>
                            <p class="text-sm text-gray-600">{{ $jadwal->peminjam->role }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $jadwal->ruangan->nama }}</p>
                            <p class="text-sm text-gray-600">{{ $jadwal->ruangan->lokasi }}</p>
                            <p class="text-sm text-gray-600">Pemilik: {{ $jadwal->ruangan->pemilik->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Diajukan Pada</p>
                            <p class="font-medium text-gray-900">{{ $jadwal->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Dates -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Tanggal Peminjaman</h2>
                        @if ($jadwal->status == 'MENUNGGU')
                            <a href="{{ route('tanggal-jadwal.create', ['jadwal_id' => $jadwal->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                Tambah Tanggal
                            </a>
                        @endif
                    </div>
                    @if ($jadwal->tanggalJadwals->count() > 0)
                        <div class="space-y-2">
                            @foreach ($jadwal->tanggalJadwals as $tanggal)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $tanggal->tanggal->locale('id')->translatedFormat('d F Y') }}</p>
                                        @if ($tanggal->jam_mulai && $tanggal->jam_berakhir)
                                            <p class="text-sm text-gray-600">{{ $tanggal->jam_mulai }} - {{ $tanggal->jam_berakhir }}</p>
                                        @else
                                            <p class="text-sm text-indigo-600"><i class="fas fa-calendar-day mr-1"></i>Full Day</p>
                                        @endif
                                        <p class="text-xs text-gray-500">Ditambahkan: {{ $tanggal->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        @if ($jadwal->status == 'MENUNGGU')
                                            <a href="{{ route('tanggal-jadwal.edit', $tanggal) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('tanggal-jadwal.destroy', $tanggal) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanggal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada tanggal peminjaman</p>
                    @endif
                </div>
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="space-y-6">
                <!-- Approval History -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Persetujuan</h2>
                    @if ($jadwal->persetujuanJadwals->count() > 0)
                        <div class="space-y-3">
                            @foreach ($jadwal->persetujuanJadwals as $persetujuan)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ $persetujuan->aktor->name }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if ($persetujuan->status == 'DISETUJUI') bg-green-100 text-green-800
                                                @elseif ($persetujuan->status == 'DITOLAK') bg-red-100 text-red-800
                                                @endif">
                                                {{ $persetujuan->status }}
                                            </span>
                                        </div>
                                        @if ($persetujuan->catatan)
                                            <p class="text-sm text-gray-600 mt-1">{{ $persetujuan->catatan }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $persetujuan->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada riwayat persetujuan</p>
                    @endif
                </div>

                <!-- Status History -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Status</h2>
                    @if ($jadwal->riwayatStatusJadwals->count() > 0)
                        <div class="space-y-3">
                            @foreach ($jadwal->riwayatStatusJadwals as $riwayat)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if ($riwayat->dari)
                                                {{ $riwayat->dari }} → {{ $riwayat->menjadi }}
                                            @else
                                                {{ $riwayat->menjadi }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">Oleh: {{ $riwayat->aktor->name }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $riwayat->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada riwayat status</p>
                    @endif
                </div>
            </div>
        </div>

        @if(Auth::user()->role == "ADMIN")
        <!-- Related Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Inspections -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Pemeriksaan Ruangan</h2>
                    @if ($jadwal->status == 'DISETUJUI')
                        <a href="{{ route('pemeriksaan.create', ['jadwal_id' => $jadwal->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Tambah Pemeriksaan
                        </a>
                    @endif
                </div>
                @if ($jadwal->pemeriksaanRuangans->count() > 0)
                    <div class="space-y-2">
                        @foreach ($jadwal->pemeriksaanRuangans as $pemeriksaan)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $pemeriksaan->getKondisiLabel() }}</p>
                                    <p class="text-xs text-gray-500">Oleh: {{ $pemeriksaan->petugas->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $pemeriksaan->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('pemeriksaan.show', $pemeriksaan) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada pemeriksaan</p>
                @endif
            </div>

            <!-- Incidents -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Insiden</h2>
                    @if ($jadwal->status == 'DISETUJUI')
                        <a href="{{ route('insiden.create', ['jadwal_id' => $jadwal->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Laporkan Insiden
                        </a>
                    @endif
                </div>
                @if ($jadwal->insidens->count() > 0)
                    <div class="space-y-2">
                        @foreach ($jadwal->insidens as $insiden)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $insiden->getTingkatLabel() }}</p>
                                    <p class="text-xs text-gray-500">Oleh: {{ $insiden->pelapor->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $insiden->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if ($insiden->isOpen()) bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $insiden->isOpen() ? 'Dibuka' : 'Ditutup' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada insiden</p>
                @endif
            </div>
        </div>
        @endif

      </div>
</x-layouts.app>

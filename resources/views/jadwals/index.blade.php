<x-layouts.app :title="__('Jadwal Peminjaman')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Jadwal Peminjaman</h1>
            @can('create', App\Models\Jadwal::class)
                <a href="{{ route('jadwal.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Ajukan Peminjaman
                </a>
            @endcan
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('jadwal.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, keperluan, ruangan..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU" {{ request('status') == 'MENUNGGU' ? 'selected' : '' }}>Menunggu</option>
                        <option value="DISETUJUI" {{ request('status') == 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
                        <option value="DITOLAK" {{ request('status') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
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
                @if (Auth::user()->role === 'ADMIN')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peminjam</label>
                    <select name="peminjam_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Peminjam</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('peminjam_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="md:col-span-2 lg:col-span-4 flex items-end space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('jadwal.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Menampilkan {{ $jadwals->firstItem() }}-{{ $jadwals->lastItem() }} dari {{ $jadwals->total() }} jadwal</p>
            @if (request()->anyFilled(['search', 'status', 'ruangan_id', 'peminjam_id']))
                <a href="{{ route('jadwal.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </div>

        <!-- Jadwal Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('jadwal.index', ['sort' => 'keperluan', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                    Keperluan
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peminjam
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ruangan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('jadwal.index', ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                    Dibuat
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($jadwals as $jadwal)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->keperluan }}</div>
                                    @if ($jadwal->tanggalJadwals->count() > 0)
                                        <div class="text-xs text-gray-500">
                                            {{ $jadwal->tanggalJadwals->first()->tanggal->locale('id')->translatedFormat('d F Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jadwal->peminjam->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $jadwal->peminjam->role }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jadwal->ruangan->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $jadwal->ruangan->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($jadwal->status == 'DISETUJUI') bg-green-100 text-green-800
                                        @elseif ($jadwal->status == 'MENUNGGU') bg-yellow-100 text-yellow-800
                                        @elseif ($jadwal->status == 'DITOLAK') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $jadwal->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $jadwal->created_at->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('jadwal.show', $jadwal) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </a>
                                    @can('update', $jadwal)
                                        @if ($jadwal->status == 'MENUNGGU')
                                            <a href="{{ route('jadwal.edit', $jadwal) }}" class="text-yellow-600 hover:text-yellow-900">
                                                Edit
                                            </a>
                                        @endif
                                    @endcan
                                    @can('delete', $jadwal)
                                        @if (in_array($jadwal->status, ['MENUNGGU', 'DITOLAK']))
                                            <form method="POST" action="{{ route('jadwal.destroy', $jadwal) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $jadwals->firstItem() }}-{{ $jadwals->lastItem() }} dari {{ $jadwals->total() }} jadwal
            </div>
            {{ $jadwals->links() }}
        </div>

        <!-- Empty State -->
        @if ($jadwals->count() == 0)
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada jadwal ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau ajukan peminjaman baru.</p>
                @can('create', App\Models\Jadwal::class)
                    <a href="{{ route('jadwal.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>Ajukan Peminjaman
                    </a>
                @endcan
            </div>
        @endif
    </div>
</x-layouts.app>

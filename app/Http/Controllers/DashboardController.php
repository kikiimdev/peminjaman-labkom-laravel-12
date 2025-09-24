<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Insiden;
use App\Models\Jadwal;
use App\Models\PemeliharaanRuangan;
use App\Models\Ruangan;
use App\Models\TanggalJadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        // Get current date info
        $today = Carbon::today();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $user = Auth::user();
        $isAdmin = $user->role === 'ADMIN';

        // Base queries for user-specific data
        $jadwalBaseQuery = Jadwal::when(! $isAdmin, function ($q) use ($user) {
            $q->where('peminjam_id', $user->id);
        });

        $insidenBaseQuery = Insiden::when(! $isAdmin, function ($q) use ($user) {
            $q->where('pelapor_id', $user->id)
                ->orWhere('ditangani_oleh', $user->id);
        });

        // 1. Overview Statistics
        $stats = [
            'total_ruangans' => Ruangan::count(),
            'total_fasilitas' => Fasilitas::count(),
            'total_users' => $isAdmin ? User::count() : 1,
            'total_jadwals' => (clone $jadwalBaseQuery)->count(),
            'jadwals_this_month' => (clone $jadwalBaseQuery)->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])->count(),
            'jadwals_last_month' => (clone $jadwalBaseQuery)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count(),
        ];

        // 2. Jadwal Status Distribution
        $jadwalStatusCounts = (clone $jadwalBaseQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // 3. Today's Schedule
        $todaySchedules = TanggalJadwal::with(['jadwal.peminjam', 'jadwal.ruangan'])
            ->whereDate('tanggal', $today)
            ->whereHas('jadwal', function ($q) use ($isAdmin, $user) {
                $q->where('status', 'DISETUJUI');
                if (! $isAdmin) {
                    $q->where('peminjam_id', $user->id);
                }
            })
            ->orderBy('jam_mulai')
            ->take(10)
            ->get();

        // 4. Upcoming Maintenance
        $upcomingMaintenance = PemeliharaanRuangan::where('dijadwalkan_pada', '>=', $today)
            ->where('status', 'TERJADWAL')
            ->with(['ruangan'])
            ->orderBy('dijadwalkan_pada')
            ->take(5)
            ->get();

        // 5. Recent Incidents
        $recentIncidents = (clone $insidenBaseQuery)
            ->whereNull('selesai_pada')
            ->with(['jadwal.peminjam', 'ruangan'])
            ->latest()
            ->take(5)
            ->get();

        // 6. Room Utilization (Last 30 days)
        $last30Days = Carbon::now()->subDays(30);
        $roomUtilization = TanggalJadwal::join('jadwals', 'tanggal_jadwals.jadwal_id', '=', 'jadwals.id')
            ->where('jadwals.status', 'DISETUJUI')
            ->when(! $isAdmin, function ($q) use ($user) {
                $q->where('jadwals.peminjam_id', $user->id);
            })
            ->whereDate('tanggal', '>=', $last30Days)
            ->select('jadwals.ruangan_id', DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(jam_berakhir, jam_mulai)) / 3600) as total_jam'), DB::raw('COUNT(DISTINCT tanggal) as total_hari'))
            ->groupBy('jadwals.ruangan_id')
            ->get()
            ->keyBy('ruangan_id');

        // 7. Monthly Trends (Last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyTrends[] = [
                'month' => $month->locale('id')->translatedFormat('F Y'),
                'total_jadwals' => (clone $jadwalBaseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'approved_jadwals' => (clone $jadwalBaseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->where('status', 'DISETUJUI')->count(),
                'total_insidens' => (clone $insidenBaseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'total_maintenance' => PemeliharaanRuangan::whereBetween('dijadwalkan_pada', [$monthStart, $monthEnd])->count(),
            ];
        }

        // 8. Room Status Summary
        $roomStatusQuery = Ruangan::withCount(['jadwals' => function ($q) use ($thisMonthStart, $thisMonthEnd, $isAdmin, $user) {
            $q->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd]);
            if (! $isAdmin) {
                $q->where('peminjam_id', $user->id);
            }
        }]);

        if ($isAdmin) {
            $roomStatusQuery->withCount(['pemeliharaanRuangans' => function ($q) {
                $q->where('status', 'TERJADWAL');
            }])->with(['pemilik']);
        }

        $roomStatusSummary = $roomStatusQuery->get()
            ->map(function ($ruangan) use ($roomUtilization, $isAdmin) {
                $utilization = $roomUtilization->get($ruangan->id);

                return [
                    'id' => $ruangan->id,
                    'nama' => $ruangan->nama,
                    'kode' => $ruangan->lokasi,
                    'pemilik' => $isAdmin ? ($ruangan->pemilik->name ?? '-') : '-',
                    'peminjaman_bulan_ini' => $ruangan->jadwals_count,
                    'pemeliharaan_terjadwal' => $isAdmin ? ($ruangan->pemeliharaan_ruangans_count ?? 0) : 0,
                    'total_jam_30_hari' => $utilization ? $utilization->total_jam : 0,
                    'total_hari_30_hari' => $utilization ? $utilization->total_hari : 0,
                ];
            });

        // 9. Pending Approvals (Admin only)
        $pendingApprovals = $isAdmin ? Jadwal::with(['peminjam', 'ruangan'])
            ->where('status', 'MENUNGGU')
            ->latest()
            ->take(5)
            ->get() : collect([]);

        // 10. Facility Status
        $facilitySummary = [
            'total_insidens' => (clone $insidenBaseQuery)->count(),
            'open_insidens' => (clone $insidenBaseQuery)->whereNull('selesai_pada')->count(),
            'high_priority_insidens' => (clone $insidenBaseQuery)->where('tingkat', 'TINGGI')->whereNull('selesai_pada')->count(),
            'total_maintenance' => PemeliharaanRuangan::count(),
            'overdue_maintenance' => PemeliharaanRuangan::where('dijadwalkan_pada', '<', $today)
                ->where('status', 'TERJADWAL')
                ->count(),
            'completed_maintenance' => PemeliharaanRuangan::where('status', 'SELESAI')
                ->count(),
        ];

        return view('dashboard', compact(
            'stats',
            'jadwalStatusCounts',
            'todaySchedules',
            'upcomingMaintenance',
            'recentIncidents',
            'monthlyTrends',
            'roomStatusSummary',
            'pendingApprovals',
            'facilitySummary'
        ));
    }

    // API endpoint for dashboard charts
    public function chartData(Request $request): array
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $type = $request->get('type', 'monthly-trends');
        $user = Auth::user();
        $isAdmin = $user->role === 'ADMIN';
        $data = [];

        switch ($type) {
            case 'monthly-trends':
                for ($i = 11; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();

                    $jadwalQuery = Jadwal::whereBetween('created_at', [$monthStart, $monthEnd]);
                    $insidenQuery = Insiden::whereBetween('created_at', [$monthStart, $monthEnd]);

                    if (! $isAdmin) {
                        $jadwalQuery->where('peminjam_id', $user->id);
                        $insidenQuery->where(function ($q) use ($user) {
                            $q->where('pelapor_id', $user->id)
                                ->orWhere('ditangani_oleh', $user->id);
                        });
                    }

                    $data[] = [
                        'label' => $month->locale('id')->translatedFormat('M Y'),
                        'jadwals' => $jadwalQuery->count(),
                        'approved' => $jadwalQuery->where('status', 'DISETUJUI')->count(),
                        'insidens' => $insidenQuery->count(),
                    ];
                }
                break;

            case 'room-utilization':
                $last30Days = Carbon::now()->subDays(30);
                $query = TanggalJadwal::join('jadwals', 'tanggal_jadwals.jadwal_id', '=', 'jadwals.id')
                    ->join('ruangans', 'jadwals.ruangan_id', '=', 'ruangans.id')
                    ->where('jadwals.status', 'DISETUJUI')
                    ->whereDate('tanggal', '>=', $last30Days);

                if (! $isAdmin) {
                    $query->where('jadwals.peminjam_id', $user->id);
                }

                $data = $query->select('ruangans.nama as room', DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(jam_berakhir, jam_mulai)) / 3600) as total_jam'))
                    ->groupBy('ruangans.nama')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'room' => $item->room,
                            'hours' => (int) $item->total_jam,
                        ];
                    })
                    ->toArray();
                break;

            case 'status-distribution':
                $query = Jadwal::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status');

                if (! $isAdmin) {
                    $query->where('peminjam_id', $user->id);
                }

                $data = $query->get()
                    ->map(function ($item) {
                        return [
                            'status' => $item->status,
                            'count' => $item->total,
                        ];
                    })
                    ->toArray();
                break;

            case 'incident-levels':
                $query = Insiden::select('tingkat', DB::raw('count(*) as total'))
                    ->groupBy('tingkat');

                if (! $isAdmin) {
                    $query->where(function ($q) use ($user) {
                        $q->where('pelapor_id', $user->id)
                            ->orWhere('ditangani_oleh', $user->id);
                    });
                }

                $data = $query->get()
                    ->map(function ($item) {
                        return [
                            'level' => $item->tingkat,
                            'count' => $item->total,
                        ];
                    })
                    ->toArray();
                break;
        }

        return [
            'type' => $type,
            'data' => $data,
        ];
    }

    // Quick stats cards
    public function quickStats(): array
    {
        if (! Auth::user()->can('viewAny', Jadwal::class)) {
            abort(403, 'Unauthorized action.');
        }

        $today = Carbon::today();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $user = Auth::user();
        $isAdmin = $user->role === 'ADMIN';

        return [
            'today_schedules' => TanggalJadwal::whereDate('tanggal', $today)
                ->whereHas('jadwal', function ($q) use ($isAdmin, $user) {
                    $q->where('status', 'DISETUJUI');
                    if (! $isAdmin) {
                        $q->where('peminjam_id', $user->id);
                    }
                })
                ->count(),
            'pending_approvals' => $isAdmin ? Jadwal::where('status', 'MENUNGGU')->count() : 0,
            'active_insidens' => Insiden::whereNull('selesai_pada')
                ->when(! $isAdmin, function ($q) use ($user) {
                    $q->where(function ($qq) use ($user) {
                        $qq->where('pelapor_id', $user->id)
                            ->orWhere('ditangani_oleh', $user->id);
                    });
                })
                ->count(),
            'overdue_maintenance' => PemeliharaanRuangan::where('dijadwalkan_pada', '<', $today)
                ->where('status', 'TERJADWAL')
                ->count(),
            'monthly_jadwals' => Jadwal::where('created_at', '>=', $thisMonthStart)
                ->when(! $isAdmin, function ($q) use ($user) {
                    $q->where('peminjam_id', $user->id);
                })
                ->count(),
            'monthly_insidens' => Insiden::where('created_at', '>=', $thisMonthStart)
                ->when(! $isAdmin, function ($q) use ($user) {
                    $q->where(function ($qq) use ($user) {
                        $qq->where('pelapor_id', $user->id)
                            ->orWhere('ditangani_oleh', $user->id);
                    });
                })
                ->count(),
        ];
    }
}

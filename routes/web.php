<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\FasilitasRuanganController;
use App\Http\Controllers\InsidenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemeliharaanRuanganController;
use App\Http\Controllers\PemeriksaanRuanganController;
use App\Http\Controllers\PersetujuanJadwalController;
use App\Http\Controllers\RiwayatStatusJadwalController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\TanggalJadwalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Public Calendar (accessible without authentication)
Route::get('/kalender', [JadwalController::class, 'calendar'])->name('kalender');

// Standalone Calendar Page (minimal layout)
Route::get('/jadwal-kalender', [JadwalController::class, 'calendarPage'])->name('calendar.page');

// Dashboard (redirect authenticated users from landing page)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Resource Routes
Route::middleware(['auth'])->group(function () {
    // Ruangan (Rooms) - Admin only for create/update/delete
    Route::resource('ruangan', RuanganController::class)->except(['index', 'show']);
    Route::get('ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('ruangan/{ruangan}', [RuanganController::class, 'show'])->name('ruangan.show');

    // Fasilitas (Facilities) - Admin only
    Route::resource('fasilitas', FasilitasController::class)->parameters(['fasilitas' => 'fasilitas']);

    // Fasilitas Ruangan (Room Facilities) - Admin only
    Route::resource('fasilitas-ruangans', FasilitasRuanganController::class);
    // ->middleware('role:ADMIN');

    // Jadwal (Schedules)
    Route::resource('jadwal', JadwalController::class);
    Route::prefix('jadwal')->name('jadwals.')->group(function () {
        Route::post('/{jadwal}/setujui', [JadwalController::class, 'approve'])->name('approve');
        Route::post('/{jadwal}/tolak', [JadwalController::class, 'reject'])->name('reject');
    });

    // Tanggal Jadwal (Schedule Dates)
    Route::resource('tanggal-jadwal', TanggalJadwalController::class);

    // Persetujuan Jadwal (Schedule Approvals)
    Route::prefix('persetujuan')->name('persetujuan.')->group(function () {
        Route::get('/', [PersetujuanJadwalController::class, 'index'])->name('index');
        Route::post('/{id}/setujui', [PersetujuanJadwalController::class, 'approve'])->name('approve');
        Route::post('/{id}/tolak', [PersetujuanJadwalController::class, 'reject'])->name('reject');
        Route::get('/export', [ExportController::class, 'exportPersetujuan'])->name('export');
    });

    // Riwayat Status Jadwal (Schedule Status History)
    Route::resource('riwayat-status', RiwayatStatusJadwalController::class)->only(['index', 'show']);

    // Pemeriksaan Ruangan (Room Inspections)
    Route::resource('pemeriksaan', PemeriksaanRuanganController::class);

    // Insiden (Incidents)
    Route::resource('insiden', InsidenController::class);
    Route::prefix('insiden')->name('insidens.')->group(function () {
        Route::get('/dashboard', [InsidenController::class, 'dashboard'])->name('dashboard');
        Route::post('/{insiden}/mark-as-completed', [InsidenController::class, 'markAsCompleted'])->name('mark-as-completed');
    });

    // Pemeliharaan Ruangan (Room Maintenance)
    Route::resource('pemeliharaan', PemeliharaanRuanganController::class);
    Route::prefix('pemeliharaan')->name('pemeliharaans.')->group(function () {
        Route::get('/calendar', [PemeliharaanRuanganController::class, 'calendar'])->name('calendar');
        Route::get('/dashboard', [PemeliharaanRuanganController::class, 'dashboard'])->name('dashboard');
    });

    // Laporan (Reports)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/rekap-peminjaman', [LaporanController::class, 'rekapPeminjaman'])->name('rekap-peminjaman');
        Route::get('/utilisasi-ruangan', [LaporanController::class, 'utilisasiRuangan'])->name('utilisasi-ruangan');
        Route::get('/status-peminjaman', [LaporanController::class, 'statusPeminjaman'])->name('status-peminjaman');
        Route::get('/sla-persetujuan', [LaporanController::class, 'slaPersetujuan'])->name('sla-persetujuan');
        Route::get('/riwayat-status', [LaporanController::class, 'riwayatPerubahanStatus'])->name('riwayat-status');
        Route::get('/pemeriksaan-ruangan', [LaporanController::class, 'pemeriksaanRuangan'])->name('pemeriksaan-ruangan');
        Route::get('/insiden', [LaporanController::class, 'insiden'])->name('insiden');
        Route::get('/pemeliharaan-ruangan', [LaporanController::class, 'pemeliharaanRuangan'])->name('pemeliharaan-ruangan');
        Route::get('/top-peminjam', [LaporanController::class, 'topPeminjam'])->name('top-peminjam');
    });

    // Exports
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/ruangan', [ExportController::class, 'masterRuangan'])->name('ruangan');
        Route::get('/fasilitas', [ExportController::class, 'masterFasilitas'])->name('fasilitas');
        Route::get('/jadwal', [ExportController::class, 'rekapPeminjaman'])->name('jadwal');
        Route::get('/pemeriksaan', [ExportController::class, 'pemeriksaanRuangan'])->name('pemeriksaan');
        Route::get('/insiden', [ExportController::class, 'insiden'])->name('insiden');
        Route::get('/pemeliharaan', [ExportController::class, 'pemeliharaanRuangan'])->name('pemeliharaan');
        Route::get('/rekap-peminjaman', [ExportController::class, 'rekapPeminjaman'])->name('rekap-peminjaman');
        Route::get('/utilisasi-ruangan', [ExportController::class, 'utilisasiRuangan'])->name('utilisasi-ruangan');
        Route::get('/status-peminjaman', [ExportController::class, 'statusPeminjaman'])->name('status-peminjaman');
        Route::get('/sla-persetujuan', [ExportController::class, 'slaPersetujuan'])->name('sla-persetujuan');
        Route::get('/riwayat-status', [ExportController::class, 'riwayatPerubahanStatus'])->name('riwayat-status');
        Route::get('/top-peminjam', [ExportController::class, 'topPeminjam'])->name('top-peminjam');
    });

    // Dashboard API (for charts)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/today-schedule', [DashboardController::class, 'getTodaySchedule'])->name('dashboard.today-schedule');
        Route::get('/dashboard/upcoming-maintenance', [DashboardController::class, 'getUpcomingMaintenance'])->name('dashboard.upcoming-maintenance');
        Route::get('/dashboard/recent-incidents', [DashboardController::class, 'getRecentIncidents'])->name('dashboard.recent-incidents');
        Route::get('/dashboard/pending-approvals', [DashboardController::class, 'getPendingApprovals'])->name('dashboard.pending-approvals');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', \App\Livewire\Settings\Profile::class)->name('profile');
        Route::get('/password', \App\Livewire\Settings\Password::class)->name('password');
        Route::get('/appearance', \App\Livewire\Settings\Appearance::class)->name('appearance');
    });
});

require __DIR__.'/auth.php';

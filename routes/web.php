<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import controller namespace Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\JenisKegiatanController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\TitikRaziaController;

// Import controller namespace Petugas
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\JadwalController as PetugasJadwalController;
use App\Http\Controllers\Petugas\LaporanController as PetugasLaporanController;
use App\Http\Controllers\Petugas\ProfilController as PetugasProfilController;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// ── ADMIN ─────────────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('petugas', PetugasController::class)
            ->parameters(['petugas' => 'petugas']);
        Route::patch('petugas/{petugas}/toggle', [PetugasController::class, 'toggleStatus'])
            ->name('petugas.toggle');

        Route::resource('shifts', ShiftController::class)->except(['create', 'edit', 'show']);
        Route::resource('lokasi', LokasiController::class)->except(['create', 'edit', 'show']);
        Route::resource('jenis-kegiatan', JenisKegiatanController::class)
            ->except(['create', 'edit', 'show'])
            ->parameters(['jenis-kegiatan' => 'jenisKegiatan']);

        // Jadwal — riwayat & restore harus SEBELUM resource
        Route::get('jadwal/riwayat', [JadwalController::class, 'riwayat'])->name('jadwal.riwayat');
        Route::post('jadwal/{id}/restore', [JadwalController::class, 'restore'])->name('jadwal.restore');
        Route::delete('jadwal/{id}/force-delete', [JadwalController::class, 'forceDelete'])->name('jadwal.force-delete');
        Route::resource('jadwal', JadwalController::class);
        Route::patch('jadwal/{jadwal}/status', [JadwalController::class, 'updateStatus'])->name('jadwal.updateStatus');

        // Penugasan
        Route::get('penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::post('penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');
        Route::delete('penugasan/{penugasan}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');

        // Titik Razia (Razia Multi-Lokasi)
        Route::get('titik-razia', [TitikRaziaController::class, 'index'])->name('titik-razia.index');
        Route::post('titik-razia', [TitikRaziaController::class, 'store'])->name('titik-razia.store');
        Route::delete('titik-razia/{titikRazia}', [TitikRaziaController::class, 'destroy'])->name('titik-razia.destroy');
        Route::post('titik-razia/{titikRazia}/checkin', [TitikRaziaController::class, 'checkin'])->name('titik-razia.checkin');

        // Laporan
        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/belum', [AdminLaporanController::class, 'belum'])->name('laporan.belum');
        Route::get('laporan/{laporan}', [AdminLaporanController::class, 'show'])->name('laporan.show');
        Route::patch('laporan/{laporan}/review', [AdminLaporanController::class, 'review'])->name('laporan.review');
        Route::patch('laporan/{laporan}/edit', [AdminLaporanController::class, 'update'])->name('laporan.update');

        // Status & Rekap
        Route::get('status', [StatusController::class, 'index'])->name('status.index');
        Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('rekap/pdf', [RekapController::class, 'exportPdf'])->name('rekap.pdf');
        Route::get('rekap/excel', [RekapController::class, 'exportExcel'])->name('rekap.excel');

        // Users
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Profil
        Route::get('profil', [AdminProfilController::class, 'show'])->name('profil');
        Route::patch('profil', [AdminProfilController::class, 'update'])->name('profil.update');
        Route::patch('profil/password', [AdminProfilController::class, 'updatePassword'])->name('profil.password');
    });

// ── PETUGAS ───────────────────────────────────────────────────────────────────
Route::prefix('petugas')
    ->name('petugas.')
    ->middleware(['auth', 'petugas'])
    ->group(function () {
        Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

        Route::get('jadwal', [PetugasJadwalController::class, 'index'])->name('jadwal.index');
        Route::get('jadwal/{jadwal}', [PetugasJadwalController::class, 'show'])->name('jadwal.show');
        Route::get('jadwal/{jadwal}/razia', [PetugasJadwalController::class, 'razia'])->name('jadwal.razia');
        Route::post('razia/{titikRazia}/checkin', [PetugasJadwalController::class, 'checkinRazia'])->name('razia.checkin');

        Route::get('laporan', [PetugasLaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/buat/{jadwal}', [PetugasLaporanController::class, 'create'])->name('laporan.create');
        Route::post('laporan/buat/{jadwal}', [PetugasLaporanController::class, 'store'])->name('laporan.store');
        Route::get('laporan/{laporan}', [PetugasLaporanController::class, 'show'])->name('laporan.show');

        Route::get('profil', [PetugasProfilController::class, 'show'])->name('profil');
        Route::patch('profil', [PetugasProfilController::class, 'update'])->name('profil.update');
        Route::patch('profil/password', [PetugasProfilController::class, 'updatePassword'])->name('profil.password');
    });

// Profile bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

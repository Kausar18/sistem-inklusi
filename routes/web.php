<?php

use App\Http\Controllers\Admin\AnggotaTimController;
use App\Http\Controllers\Admin\BidangUsahaController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\LegalitasController;
use App\Http\Controllers\Admin\StartupController as AdminStartupController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\TargetOutputController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PendampinganController;
use App\Http\Controllers\SetupSementaraController;
use App\Http\Controllers\StartupController;
use Illuminate\Support\Facades\Route;

// ====================================================================
// HALAMAN PUBLIK (bisa dilihat siapa saja, tanpa login)
// ====================================================================

// Halaman utama: beranda program
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Statistik program (sebelumnya berada di halaman utama)
Route::get('/statistik', [DashboardController::class, 'index'])->name('statistik');

// Daftar startup dengan pencarian dan filter
Route::get('/startup', [StartupController::class, 'index'])->name('startup.index');

// Detail satu startup
Route::get('/startup/{startup}', [StartupController::class, 'show'])->name('startup.show');

// Infografis profil startup (siap cetak / simpan PDF)
Route::get('/startup/{startup}/infografis', [StartupController::class, 'infografis'])
    ->name('startup.infografis');

// ====================================================================
// SETUP SEMENTARA (hapus setelah dipakai — lihat SetupSementaraController)
// ====================================================================

Route::get('/setup-admin/{token}', [SetupSementaraController::class, 'create'])->name('setup.admin.create');
Route::post('/setup-admin/{token}', [SetupSementaraController::class, 'store'])->name('setup.admin.store');

Route::get('/setup-hapus-startup/{token}', [SetupSementaraController::class, 'formHapusStartup'])->name('setup.hapus-startup.form');
Route::post('/setup-hapus-startup/{token}', [SetupSementaraController::class, 'hapusSemuaStartup'])->name('setup.hapus-startup.store');

// ====================================================================
// LOGIN / LOGOUT
// ====================================================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ====================================================================
// KHUSUS PENGGUNA YANG SUDAH MASUK (admin)
// ====================================================================

Route::middleware('auth')->group(function () {

    // ---------------------------------------------------------- Pendampingan
    Route::post('/startup/{startup}/pendampingan', [PendampinganController::class, 'store'])
        ->name('pendampingan.store');

    Route::delete('/startup/{startup}/pendampingan/{pendampingan}', [PendampinganController::class, 'destroy'])
        ->name('pendampingan.destroy');

    // -------------------------------------------------- Pemantauan kinerja
    Route::post('/startup/{startup}/monitoring', [MonitoringController::class, 'store'])
        ->name('monitoring.store');

    Route::delete('/startup/{startup}/monitoring/{monitoring}', [MonitoringController::class, 'destroy'])
        ->name('monitoring.destroy');

    // ------------------------------------------------------- Panel admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/import', [ImportController::class, 'create'])->name('import.create');
        Route::post('/import', [ImportController::class, 'store'])->name('import.store');
        Route::post('/import/proses', [ImportController::class, 'proses'])->name('import.proses');

        Route::resource('startup', AdminStartupController::class)->except('show');

        Route::resource('bidang-usaha', BidangUsahaController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::post('/startup/{startup}/anggota-tim', [AnggotaTimController::class, 'store'])
            ->name('startup.anggota-tim.store');
        Route::delete('/startup/{startup}/anggota-tim/{anggotaTim}', [AnggotaTimController::class, 'destroy'])
            ->name('startup.anggota-tim.destroy');

        Route::post('/startup/{startup}/legalitas', [LegalitasController::class, 'store'])
            ->name('startup.legalitas.store');
        Route::delete('/startup/{startup}/legalitas/{legalitas}', [LegalitasController::class, 'destroy'])
            ->name('startup.legalitas.destroy');

        Route::post('/startup/{startup}/dokumentasi', [DokumentasiController::class, 'store'])
            ->name('startup.dokumentasi.store');
        Route::delete('/startup/{startup}/dokumentasi/{dokumentasi}', [DokumentasiController::class, 'destroy'])
            ->name('startup.dokumentasi.destroy');

        Route::post('/startup/{startup}/target-output', [TargetOutputController::class, 'store'])
            ->name('startup.target-output.store');
        Route::delete('/startup/{startup}/target-output/{targetOutput}', [TargetOutputController::class, 'destroy'])
            ->name('startup.target-output.destroy');
    });
});

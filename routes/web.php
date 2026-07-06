<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HafalanController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\ValidasiController;
use Illuminate\Support\Facades\Route;

// =========================================================
// HALAMAN ROOT
// =========================================================
Route::get('/', function () {
    return view('landing');
})-> name('landing');

// =========================================================
// AUTH — LOGIN & LOGOUT (TANPA REGISTER)
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// ROUTE GROUP SANTRI
// =========================================================
Route::middleware(['auth', 'role:santri'])
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {

        // Mhs 1: Dashboard & progres 30 juz
        Route::get('/dashboard', [DashboardController::class, 'santri'])->name('dashboard');
        Route::get('/hafalan/progres', [DashboardController::class, 'progres'])->name('hafalan.progres');

        // Mhs 2: Quran Reader
        Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
        Route::get('/quran/{nomor}', [QuranController::class, 'show'])->name('quran.show');

        // Mhs 3: Form input & riwayat setoran hafalan
        // PENTING: /hafalan/create harus SEBELUM /hafalan
        // agar Laravel tidak salah baca 'create' sebagai parameter
        Route::get('/hafalan', [HafalanController::class, 'index'])->name('hafalan.index');
        Route::get('/hafalan/create', [HafalanController::class, 'create'])->name('hafalan.create');
        Route::post('/hafalan', [HafalanController::class, 'store'])->name('hafalan.store');

    });

// =========================================================
// ROUTE GROUP GURU
// =========================================================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // Mhs 1: Dashboard guru
        Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');

        // Mhs 3: Validasi setoran hafalan
        Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi.index');
        Route::get('/validasi/{id}/grade', [ValidasiController::class, 'grade'])->name('validasi.grade');
        Route::post('/validasi/{id}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');

    });
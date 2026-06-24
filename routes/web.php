<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================================================
// HALAMAN ROOT
// =========================================================
Route::get('/', function () {

    if (Auth::check()) {

        if (Auth::user()->role === 'guru') {
            return redirect()->route('guru.dashboard');
        }

        return redirect()->route('santri.dashboard');
    }

    return redirect()->route('login');
});

// =========================================================
// ROUTE AUTH
// =========================================================
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// ROUTE SANTRI
// =========================================================
Route::middleware(['auth', 'role:santri'])
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'santri'])
            ->name('dashboard');

        Route::get('/hafalan/progres', [DashboardController::class, 'progres'])
            ->name('hafalan.progres');

        // Route::get('/hafalan', [HafalanController::class, 'index'])->name('hafalan.index');
        // Route::get('/hafalan/create', [HafalanController::class, 'create'])->name('hafalan.create');
        // Route::post('/hafalan', [HafalanController::class, 'store'])->name('hafalan.store');

        // Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
        // Route::post('/jurnal', [JurnalController::class, 'store'])->name('jurnal.store');

        // Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
        // Route::get('/quran/{surah}', [QuranController::class, 'show'])->name('quran.show');
    });

// =========================================================
// ROUTE GURU
// =========================================================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'guru'])
            ->name('dashboard');

        // Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi.index');
        // Route::post('/validasi/{hafalan}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');

        // Route::get('/buku-induk', [BukuIndukController::class, 'index'])->name('buku-induk.index');
        // Route::get('/buku-induk/{santri}', [BukuIndukController::class, 'show'])->name('buku-induk.show');

        // Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        // Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        // Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    });
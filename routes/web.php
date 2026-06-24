<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root ke halaman login
Route::redirect('/', '/login');

// =========================
// Blok 1: Login & Logout
// =========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// =========================
// Blok 2: Area Santri
// =========================
Route::prefix('santri')
    ->middleware(['auth', 'role:santri'])
    ->name('santri.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'santri'])->name('dashboard');
        Route::get('/hafalan/progres', [DashboardController::class, 'progres'])->name('hafalan.progres');
    });

// =========================
// Blok 3: Area Guru
// =========================
Route::prefix('guru')
    ->middleware(['auth', 'role:guru'])
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');
    });
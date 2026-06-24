<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// =========================================================
// HALAMAN ROOT
// Saat user buka http://mengajiyuk.test/
// langsung diarahkan ke halaman login
// =========================================================
Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================
// ROUTE AUTH — LOGIN & LOGOUT
//
// Middleware 'guest' artinya hanya bisa diakses oleh
// user yang BELUM login. Jika sudah login dan mencoba
// buka /login maka Laravel otomatis redirect ke home.
//
// TIDAK ADA route /register di sini.
// Akun hanya bisa dibuat melalui seeder.
// =========================================================
Route::middleware('guest')->group(function () {

    // Tampilkan form login
    // GET /login → AuthController@showLogin
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    // Proses form login yang dikirim
    // POST /login → AuthController@login
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

});

// Route logout menggunakan POST bukan GET untuk keamanan CSRF
// Middleware auth memastikan hanya user yang login yang bisa logout
// POST /logout → AuthController@logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// ROUTE GROUP SANTRI
//
// Syarat akses:
// - 'auth'        : wajib sudah login
// - 'role:santri' : role user harus 'santri'
//
// Jika guru mencoba buka /santri/dashboard maka
// RoleMiddleware akan redirect ke /guru/dashboard
//
// Semua URL di sini diawali dengan /santri/
// Semua nama route diawali dengan santri.
// =========================================================
Route::middleware(['auth', 'role:santri'])
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {

        // Dashboard utama santri
        // URL  : /santri/dashboard
        // Name : santri.dashboard
        Route::get('/dashboard', [DashboardController::class, 'santri'])
            ->name('dashboard');

        // Halaman visualisasi progres 30 juz
        // URL  : /santri/hafalan/progres
        // Name : santri.hafalan.progres
        Route::get('/hafalan/progres', [DashboardController::class, 'progres'])
            ->name('hafalan.progres');

        // =====================================================
        // PLACEHOLDER untuk Mhs 2 & Mhs 3
        // Uncomment saat controller sudah dibuat
        // =====================================================

        // Mhs 3: Form input dan riwayat setoran hafalan
        // Route::get('/hafalan', [HafalanController::class, 'index'])->name('hafalan.index');
        // Route::get('/hafalan/create', [HafalanController::class, 'create'])->name('hafalan.create');
        // Route::post('/hafalan', [HafalanController::class, 'store'])->name('hafalan.store');

        // Mhs 2: Jurnal ibadah harian
        // Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
        // Route::post('/jurnal', [JurnalController::class, 'store'])->name('jurnal.store');

        // Mhs 2: Quran Reader integrasi EQuran.id API
        // Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
        // Route::get('/quran/{surah}', [QuranController::class, 'show'])->name('quran.show');

    });

// =========================================================
// ROUTE GROUP GURU
//
// Syarat akses:
// - 'auth'      : wajib sudah login
// - 'role:guru' : role user harus 'guru'
//
// Jika santri mencoba buka /guru/dashboard maka
// RoleMiddleware akan redirect ke /santri/dashboard
//
// Semua URL di sini diawali dengan /guru/
// Semua nama route diawali dengan guru.
// =========================================================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // Dashboard utama guru
        // URL  : /guru/dashboard
        // Name : guru.dashboard
        Route::get('/dashboard', [DashboardController::class, 'guru'])
            ->name('dashboard');

        // =====================================================
        // PLACEHOLDER untuk Mhs 3
        // Uncomment saat controller sudah dibuat
        // =====================================================

        // Mhs 3: Antrean dan validasi setoran hafalan
        // Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi.index');
        // Route::post('/validasi/{hafalan}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');

        // Mhs 3: Buku induk dan riwayat hafalan per santri
        // Route::get('/buku-induk', [BukuIndukController::class, 'index'])->name('buku-induk.index');
        // Route::get('/buku-induk/{santri}', [BukuIndukController::class, 'show'])->name('buku-induk.show');

        // Mhs 3: Laporan dan ekspor PDF atau Excel
        // Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        // Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        // Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

    });
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini bekerja sebagai penjaga pintu untuk setiap route group.
     * Memastikan guru tidak bisa akses halaman santri dan sebaliknya.
     *
     * Cara pakai di routes/web.php:
     *   Route::middleware(['auth', 'role:guru'])->group(...)
     *   Route::middleware(['auth', 'role:santri'])->group(...)
     *
     * Parameter $role dikirim dari routes/web.php setelah tanda titik dua.
     * Contoh: 'role:guru' maka $role = 'guru'
     *
     * Alur kerja:
     * 1. Cek apakah user sudah login
     * 2. Ambil role user yang sedang login
     * 3. Cek apakah role user cocok dengan role yang diizinkan
     * 4. Jika cocok lanjutkan ke halaman tujuan
     * 5. Jika tidak cocok redirect ke dashboard milik role user tersebut
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Langkah 1: Pastikan user sudah login
        // Jika belum login arahkan ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Langkah 2: Ambil role user yang sedang login
        $userRole = Auth::user()->role;

        // Langkah 3 & 4: Cek apakah role cocok
        // Jika cocok izinkan akses lanjutkan ke halaman tujuan
        if ($userRole === $role) {
            return $next($request);
        }

        // Langkah 5: Role tidak cocok
        // Redirect ke dashboard yang sesuai dengan role user
        // Ini lebih user friendly daripada menampilkan halaman error 403

        if ($userRole === 'guru') {
            // User adalah guru tapi mencoba akses halaman santri
            return redirect()->route('guru.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        if ($userRole === 'santri') {
            // User adalah santri tapi mencoba akses halaman guru
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Fallback: role tidak dikenali sama sekali
        // Paksa logout dan kembali ke halaman login
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('error', 'Sesi tidak valid. Silakan login kembali.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * METHOD: showLogin()
     *
     * Menampilkan halaman form login.
     *
     * Alur:
     * 1. Cek apakah user sudah login sebelumnya
     * 2. Jika sudah login langsung redirect ke dashboard sesuai role
     * 3. Jika belum login tampilkan halaman login
     */
    public function showLogin(): View|RedirectResponse
    {
        // Jika sudah login, tidak perlu tampilkan form login lagi
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * METHOD: login()
     *
     * Memproses form login yang dikirim oleh user.
     *
     * Alur:
     * 1. Validasi input email dan password
     * 2. Coba login menggunakan Auth::attempt()
     * 3. Jika berhasil regenerate session lalu redirect by role
     * 4. Jika gagal kembali ke form login dengan pesan error
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input dari form login
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba login dengan email dan password
        // Parameter kedua mengaktifkan fitur remember me
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Login berhasil
            // Regenerate session ID untuk mencegah session fixation attack
            $request->session()->regenerate();

            // Baca role user yang baru saja login
            $role = Auth::user()->role;

            // Redirect ke dashboard berdasarkan role
            return $this->redirectByRole($role);
        }

        // Login gagal
        // Kembalikan ke halaman login dengan pesan error di field email
        // withInput mengisi ulang field email agar tidak perlu ketik ulang
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
    }

    /**
     * METHOD: logout()
     *
     * Memproses permintaan logout dari user.
     *
     * Alur:
     * 1. Hapus data autentikasi user dari sesi
     * 2. Hapus seluruh data sesi
     * 3. Generate ulang CSRF token
     * 4. Redirect ke halaman login dengan pesan sukses
     */
    public function logout(Request $request): RedirectResponse
    {
        // Hapus data autentikasi dari sesi
        Auth::logout();

        // Hapus seluruh data sesi yang tersimpan
        $request->session()->invalidate();

        // Generate ulang token CSRF untuk keamanan
        $request->session()->regenerateToken();

        // Kembali ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar. Sampai jumpa!');
    }

    /**
     * METHOD PRIVATE: redirectByRole()
     *
     * Helper internal untuk mengarahkan user ke dashboard
     * berdasarkan role mereka setelah login berhasil.
     *
     * 'guru'   → ke /guru/dashboard
     * 'santri' → ke /santri/dashboard
     * default  → kembali ke login jika role tidak dikenali
     */
    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'guru'   => redirect()->route('guru.dashboard'),
            'santri' => redirect()->route('santri.dashboard'),
            default  => redirect()->route('login')
                             ->withErrors(['email' => 'Role tidak dikenali. Hubungi administrator.']),
        };
    }
}
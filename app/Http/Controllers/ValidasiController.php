<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    /**
     * METHOD: index()
     *
     * Menampilkan halaman antrean semua setoran yang
     * menunggu penilaian dari guru.
     *
     * Route: GET /guru/validasi → guru.validasi.index
     */
    public function index(): View
    {
        // Ambil semua setoran pending beserta data santri
        // oldest() agar yang paling lama menunggu tampil duluan
        $setoranPending = Hafalan::pending()
            ->with('santri')
            ->oldest()
            ->paginate(20);

        // Ambil setoran yang sudah dinilai guru ini hari ini
        $setoranDinilaiHariIni = Hafalan::approved()
            ->where('dinilai_oleh', Auth::id())
            ->whereDate('updated_at', today())
            ->with('santri')
            ->latest('updated_at')
            ->get();

        // Statistik untuk kartu di header halaman
        $totalPending        = Hafalan::pending()->count();
        $totalDinilaiHariIni = Hafalan::approved()
            ->where('dinilai_oleh', Auth::id())
            ->whereDate('updated_at', today())
            ->count();

        return view('guru.validasi.index', compact(
            'setoranPending',
            'setoranDinilaiHariIni',
            'totalPending',
            'totalDinilaiHariIni',
        ));
    }

    /**
     * METHOD: grade($id)
     *
     * Menampilkan form penilaian untuk satu setoran tertentu.
     *
     * Route: GET /guru/validasi/{id}/grade → guru.validasi.grade
     */
    public function grade(int $id): View|RedirectResponse
    {
        // Cari setoran berdasarkan ID beserta data santrinya
        $hafalan = Hafalan::with('santri')->findOrFail($id);

        // Cegah akses ke form jika sudah dinilai
        if ($hafalan->status === 'approved') {
            return redirect()->route('guru.validasi.index')
                ->with('error', "Setoran {$hafalan->nama_surah} milik {$hafalan->santri->name} sudah pernah dinilai.");
        }

        return view('guru.validasi.grade', compact('hafalan'));
    }

    /**
     * METHOD: approve($id)
     *
     * Memproses penilaian guru untuk satu setoran hafalan.
     *
     * Alur:
     * 1. Validasi input nilai wajib (A/B/C/D), catatan opsional
     * 2. Cari data setoran berdasarkan ID
     * 3. Update status ke approved, simpan nilai dan catatan
     * 4. Catat ID guru yang menilai
     * 5. Redirect ke antrean dengan pesan sukses
     *
     * Route: POST /guru/validasi/{id}/approve → guru.validasi.approve
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        // Validasi input dari form penilaian
        $validated = $request->validate([
            'nilai'        => ['required', 'in:A,B,C,D'],
            'catatan_guru' => ['nullable', 'string', 'max:500'],
        ], [
            'nilai.required' => 'Pilih nilai untuk setoran ini (A, B, C, atau D).',
            'nilai.in'       => 'Nilai tidak valid. Pilih A, B, C, atau D.',
            'catatan_guru.max' => 'Catatan maksimal 500 karakter.',
        ]);

        // Cari data setoran
        $hafalan = Hafalan::with('santri')->findOrFail($id);

        // Cegah penilaian ganda
        if ($hafalan->status === 'approved') {
            return redirect()->route('guru.validasi.index')
                ->with('error', 'Setoran ini sudah pernah dinilai sebelumnya.');
        }

        // Update setoran dengan nilai dari guru
        $hafalan->update([
            'status'       => 'approved',
            'nilai'        => $validated['nilai'],
            'catatan_guru' => $validated['catatan_guru'] ?? null,
            'dinilai_oleh' => Auth::id(),
        ]);

        return redirect()->route('guru.validasi.index')
            ->with('success', "Setoran {$hafalan->nama_surah} milik {$hafalan->santri->name} berhasil dinilai dengan nilai {$validated['nilai']}.");
    }
}
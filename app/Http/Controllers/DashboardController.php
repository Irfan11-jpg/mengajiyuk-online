<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Daftar 30 juz dan nama surah perwakilan (disederhanakan untuk demo).
     */
    private function daftarJuz(): array
    {
        $namaSurah = [
            1 => 'Al-Fatihah', 2 => 'Al-Baqarah', 3 => 'Ali Imran', 4 => 'An-Nisa',
            5 => 'Al-Maidah', 6 => 'Al-Anam', 7 => 'Al-Araf', 8 => 'Al-Anfal',
            9 => 'At-Taubah', 10 => 'Yunus', 11 => 'Hud', 12 => 'Yusuf',
            13 => 'Ar-Rad', 14 => 'Ibrahim', 15 => 'Al-Hijr', 16 => 'An-Nahl',
            17 => 'Al-Isra', 18 => 'Al-Kahf', 19 => 'Maryam', 20 => 'Taha',
            21 => 'Al-Anbiya', 22 => 'Al-Hajj', 23 => 'Al-Muminun', 24 => 'An-Nur',
            25 => 'Al-Furqan', 26 => 'Asy-Syuara', 27 => 'An-Naml', 28 => 'Al-Qasas',
            29 => 'Al-Ankabut', 30 => 'An-Nas (& sekitarnya)',
        ];

        return $namaSurah;
    }

    /**
     * Dashboard untuk role santri.
     */
    public function santri(): View
    {
        $user = Auth::user();

        $totalApproved = Hafalan::where('user_id', $user->id)->approved()->count();
        $totalZiyadah = Hafalan::where('user_id', $user->id)->ziyadah()->approved()->count();
        $totalMurojaah = Hafalan::where('user_id', $user->id)->murojaah()->approved()->count();

        $totalJuzTersentuh = Hafalan::where('user_id', $user->id)
            ->approved()
            ->distinct('nomor_surah')
            ->count('nomor_surah');

        $progressPersen = (int) round(($totalJuzTersentuh / 30) * 100);

        $juzData = [];
        $namaSurahJuz = $this->daftarJuz();
        $hafalanPerJuz = Hafalan::where('user_id', $user->id)
            ->approved()
            ->get()
            ->groupBy('nomor_surah');

        for ($i = 1; $i <= 30; $i++) {
            $juzData[] = [
                'nomor' => $i,
                'nama' => $namaSurahJuz[$i] ?? "Juz $i",
                'selesai' => $hafalanPerJuz->has($i),
            ];
        }

        $chartLabels = collect($juzData)->pluck('nama')->take(10)->all();
        $chartValues = collect($juzData)->take(10)->map(fn ($j) => $j['selesai'] ? 100 : 0)->all();

        $setoranTerbaru = Hafalan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('santri.dashboard', [
            'totalApproved' => $totalApproved,
            'totalZiyadah' => $totalZiyadah,
            'totalMurojaah' => $totalMurojaah,
            'progressPersen' => $progressPersen,
            'juzData' => $juzData,
            'setoranTerbaru' => $setoranTerbaru,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ]);
    }

    /**
     * Halaman detail progres 30 juz untuk santri.
     */
    public function progres(): View
    {
        $user = Auth::user();
        $namaSurahJuz = $this->daftarJuz();

        $hafalanPerJuz = Hafalan::where('user_id', $user->id)
            ->approved()
            ->get()
            ->groupBy('nomor_surah');

        $juzData = [];
        for ($i = 1; $i <= 30; $i++) {
            $hafalanJuzIni = $hafalanPerJuz->get($i);
            $juzData[] = [
                'nomor' => $i,
                'nama' => $namaSurahJuz[$i] ?? "Juz $i",
                'selesai' => $hafalanPerJuz->has($i),
                'jumlah_setoran' => $hafalanJuzIni ? $hafalanJuzIni->count() : 0,
            ];
        }

        $totalSelesai = collect($juzData)->where('selesai', true)->count();
        $progressPersen = (int) round(($totalSelesai / 30) * 100);

        if ($progressPersen >= 100) {
            $pesanMotivasi = 'Masya Allah, hafalan 30 juz Anda telah lengkap! Pertahankan dengan terus murojaah. 🎉';
        } elseif ($progressPersen >= 75) {
            $pesanMotivasi = 'Hampir selesai! Sedikit lagi menuju khatam 30 juz, semangat terus. 💪';
        } elseif ($progressPersen >= 50) {
            $pesanMotivasi = 'Sudah separuh jalan! Konsistensi adalah kunci, terus lanjutkan. 🌟';
        } elseif ($progressPersen >= 25) {
            $pesanMotivasi = 'Progres yang bagus, terus tingkatkan hafalan setiap harinya. 🌱';
        } else {
            $pesanMotivasi = 'Setiap ayat yang dihafal adalah langkah menuju kebaikan. Mulai dan konsisten! 🤲';
        }

        return view('santri.hafalan.progres', [
            'juzData' => $juzData,
            'progressPersen' => $progressPersen,
            'pesanMotivasi' => $pesanMotivasi,
        ]);
    }

    /**
     * Dashboard untuk role guru.
     */
    public function guru(): View
    {
        $totalSantri = User::santri()->count();

        $setoranHariIni = Hafalan::whereDate('created_at', Carbon::today())->count();

        $totalPending = Hafalan::pending()->count();

        $daftarSantri = User::santri()
            ->withCount([
                'hafalans as total_hafalan' => fn ($q) => $q->approved(),
            ])
            ->get()
            ->map(function ($santri) {
                $totalJuzSantri = Hafalan::where('user_id', $santri->id)
                    ->approved()
                    ->distinct('nomor_surah')
                    ->count('nomor_surah');

                $santri->progress_persen = (int) round(($totalJuzSantri / 30) * 100);

                return $santri;
            });

        $setoranPending = Hafalan::pending()
            ->with('santri')
            ->latest()
            ->take(8)
            ->get();

        $chart7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $chart7Hari[] = [
                'label' => $tanggal->translatedFormat('D, d M'),
                'jumlah' => Hafalan::whereDate('created_at', $tanggal)->count(),
            ];
        }

        return view('guru.dashboard', [
            'totalSantri' => $totalSantri,
            'setoranHariIni' => $setoranHariIni,
            'totalPending' => $totalPending,
            'daftarSantri' => $daftarSantri,
            'setoranPending' => $setoranPending,
            'chartLabels' => collect($chart7Hari)->pluck('label')->all(),
            'chartValues' => collect($chart7Hari)->pluck('jumlah')->all(),
        ]);
    }
}
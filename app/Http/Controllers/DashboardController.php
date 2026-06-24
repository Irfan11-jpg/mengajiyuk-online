<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * METHOD: santri()
     * Dashboard utama santri.
     * Menampilkan statistik, chart, grid 30 juz, dan setoran terbaru.
     */
    public function santri(): View
    {
        $santri = Auth::user();

        $totalApproved = $santri->hafalans()->approved()->count();
        $totalZiyadah  = $santri->hafalans()->approved()->ziyadah()->count();
        $totalMurojaah = $santri->hafalans()->approved()->murojaah()->count();

        $hafalanPerSurah = $santri->hafalans()
            ->approved()
            ->selectRaw('nomor_surah, nama_surah, COUNT(*) as total')
            ->groupBy('nomor_surah', 'nama_surah')
            ->orderBy('nomor_surah')
            ->get();

        $surahSelesai = $santri->hafalans()
            ->approved()
            ->distinct('nomor_surah')
            ->count('nomor_surah');

        $progressPersen = $surahSelesai > 0
            ? round(($surahSelesai / 114) * 100, 1)
            : 0;

        $juzData = $this->buildJuzData($santri);

        $setoranTerbaru = $santri->hafalans()
            ->with('guru')
            ->latest()
            ->take(5)
            ->get();

        return view('santri.dashboard', compact(
            'santri',
            'totalApproved',
            'totalZiyadah',
            'totalMurojaah',
            'hafalanPerSurah',
            'surahSelesai',
            'progressPersen',
            'juzData',
            'setoranTerbaru',
        ));
    }

    /**
     * METHOD: progres()
     * Halaman detail visualisasi 30 juz.
     */
    public function progres(): View
    {
        $santri = Auth::user();

        $juzData = $this->buildJuzData($santri);

        $surahSelesai = $santri->hafalans()
            ->approved()
            ->distinct('nomor_surah')
            ->count('nomor_surah');

        $progressPersen = $surahSelesai > 0
            ? round(($surahSelesai / 114) * 100, 1)
            : 0;

        return view('santri.hafalan.progres', compact(
            'santri',
            'juzData',
            'surahSelesai',
            'progressPersen',
        ));
    }

    /**
     * METHOD: guru()
     * Dashboard utama guru.
     * Menampilkan statistik, chart setoran, daftar santri, dan pending.
     */
    public function guru(): View
    {
        $guru = Auth::user();

        $totalSantri    = User::santri()->count();
        $setoranHariIni = Hafalan::whereDate('created_at', today())->count();
        $setoranPending = Hafalan::pending()->count();

        $daftarSantri = User::santri()
            ->withCount([
                'hafalans as total_hafalan',
                'hafalans as total_approved' => fn ($q) => $q->approved(),
                'hafalans as total_pending'  => fn ($q) => $q->pending(),
            ])
            ->orderBy('name')
            ->get();

        $setoranMenunggu = Hafalan::pending()
            ->with('santri')
            ->latest()
            ->take(10)
            ->get();

        $setoranPerHariRaw = Hafalan::selectRaw("DATE(created_at) as tanggal, COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal  = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('d M');
            $data[]   = $setoranPerHariRaw[$tanggal] ?? 0;
        }

        return view('guru.dashboard', compact(
            'guru',
            'totalSantri',
            'setoranHariIni',
            'setoranPending',
            'daftarSantri',
            'setoranMenunggu',
            'labels',
            'data',
        ));
    }

    /**
     * METHOD PRIVATE: buildJuzData()
     * Membangun array data 30 juz beserta status ada tidaknya hafalan.
     */
    private function buildJuzData(User $santri): array
    {
        $juzMapping = [
            1  => ['start' => 1,   'end' => 2],
            2  => ['start' => 2,   'end' => 2],
            3  => ['start' => 2,   'end' => 3],
            4  => ['start' => 3,   'end' => 4],
            5  => ['start' => 4,   'end' => 4],
            6  => ['start' => 4,   'end' => 5],
            7  => ['start' => 5,   'end' => 6],
            8  => ['start' => 6,   'end' => 7],
            9  => ['start' => 7,   'end' => 8],
            10 => ['start' => 8,   'end' => 9],
            11 => ['start' => 9,   'end' => 11],
            12 => ['start' => 11,  'end' => 12],
            13 => ['start' => 12,  'end' => 14],
            14 => ['start' => 15,  'end' => 16],
            15 => ['start' => 17,  'end' => 18],
            16 => ['start' => 18,  'end' => 20],
            17 => ['start' => 21,  'end' => 22],
            18 => ['start' => 23,  'end' => 25],
            19 => ['start' => 25,  'end' => 27],
            20 => ['start' => 27,  'end' => 29],
            21 => ['start' => 29,  'end' => 33],
            22 => ['start' => 33,  'end' => 36],
            23 => ['start' => 36,  'end' => 39],
            24 => ['start' => 39,  'end' => 41],
            25 => ['start' => 41,  'end' => 45],
            26 => ['start' => 46,  'end' => 51],
            27 => ['start' => 51,  'end' => 57],
            28 => ['start' => 58,  'end' => 66],
            29 => ['start' => 67,  'end' => 77],
            30 => ['start' => 78,  'end' => 114],
        ];

        $surahDisetorkan = $santri->hafalans()
            ->approved()
            ->pluck('nomor_surah')
            ->unique()
            ->toArray();

        $juzData = [];
        foreach ($juzMapping as $juz => $range) {
            $adaHafalan = false;
            for ($s = $range['start']; $s <= $range['end']; $s++) {
                if (in_array($s, $surahDisetorkan)) {
                    $adaHafalan = true;
                    break;
                }
            }
            $juzData[$juz] = [
                'juz'         => $juz,
                'ada_hafalan' => $adaHafalan,
                'start_surah' => $range['start'],
                'end_surah'   => $range['end'],
            ];
        }

        return $juzData;
    }
}
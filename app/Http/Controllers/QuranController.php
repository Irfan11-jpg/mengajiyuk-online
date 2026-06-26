<?php

namespace App\Http\Controllers;

use App\Services\QuranApiService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuranController extends Controller
{
    public function __construct(
        private QuranApiService $quranApi
    ) {}

    public function index(): View
    {
        $surahList    = $this->quranApi->getAllSurah();
        $totalSurah   = count($surahList);
        $surahMakkah  = collect($surahList)->where('tempatTurun', 'Mekah')->count();
        $surahMadinah = collect($surahList)->where('tempatTurun', 'Madinah')->count();

        return view('santri.quran.index', compact(
            'surahList', 'totalSurah', 'surahMakkah', 'surahMadinah'
        ));
    }

    public function show(int $nomor): View|RedirectResponse
    {
        if ($nomor < 1 || $nomor > 114) {
            return redirect()->route('santri.quran.index')
                ->with('error', 'Nomor surah tidak valid.');
        }

        $surah = $this->quranApi->getSurah($nomor);

        if (!$surah) {
            return redirect()->route('santri.quran.index')
                ->with('error', 'Gagal memuat data surah. Cek koneksi internet.');
        }

        $surahSebelumnya = $nomor > 1   ? $this->quranApi->getSurah($nomor - 1) : null;
        $surahBerikutnya = $nomor < 114 ? $this->quranApi->getSurah($nomor + 1) : null;
        $totalAyat       = count($surah['ayat'] ?? []);

        return view('santri.quran.show', compact(
            'surah', 'surahSebelumnya', 'surahBerikutnya', 'totalAyat'
        ));
    }
}
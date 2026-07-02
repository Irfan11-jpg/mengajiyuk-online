<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Services\QuranApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HafalanController extends Controller
{
    /**
     * QuranApiService diinject untuk ambil daftar surah
     * yang dipakai di dropdown form input setoran.
     */
    public function __construct(
        private QuranApiService $quranApi
    ) {}

    /**
     * METHOD: index()
     *
     * Menampilkan riwayat semua setoran hafalan milik
     * santri yang sedang login, diurutkan dari terbaru.
     *
     * Route: GET /santri/hafalan → santri.hafalan.index
     */
    public function index(): View
    {
        $santri = Auth::user();

        // Ambil semua setoran milik santri ini
        // with('guru') agar nama guru yang menilai ikut dimuat
        // latest() agar yang terbaru tampil di atas
        // paginate(15) agar tidak semua data dimuat sekaligus
        $hafalans = $santri->hafalans()
            ->with('guru')
            ->latest()
            ->paginate(15);

        // Hitung statistik ringkasan untuk kartu di atas tabel
        $totalSetoran  = $santri->hafalans()->count();
        $totalApproved = $santri->hafalans()->approved()->count();
        $totalPending  = $santri->hafalans()->pending()->count();
        $totalZiyadah  = $santri->hafalans()->ziyadah()->count();
        $totalMurojaah = $santri->hafalans()->murojaah()->count();

        return view('santri.hafalan.index', compact(
            'hafalans',
            'totalSetoran',
            'totalApproved',
            'totalPending',
            'totalZiyadah',
            'totalMurojaah',
        ));
    }

    /**
     * METHOD: create()
     *
     * Menampilkan form untuk input setoran hafalan baru.
     * Dropdown surah diambil dari EQuran.id API.
     *
     * Route: GET /santri/hafalan/create → santri.hafalan.create
     */
    public function create(): View
    {
        // Ambil daftar 114 surah dari EQuran.id API
        // Sudah di-cache di service sehingga tidak selalu hit API
        $surahList = $this->quranApi->getAllSurah();

        return view('santri.hafalan.create', compact('surahList'));
    }

    /**
     * METHOD: store()
     *
     * Menyimpan setoran hafalan baru ke database.
     *
     * Alur:
     * 1. Validasi semua input dari form
     * 2. Cari nama surah berdasarkan nomor yang dipilih
     * 3. Simpan ke tabel hafalans dengan status 'pending'
     * 4. Redirect ke riwayat dengan pesan sukses
     *
     * Route: POST /santri/hafalan → santri.hafalan.store
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi semua input dari form
        $validated = $request->validate([
            'nomor_surah' => ['required', 'integer', 'min:1', 'max:114'],
            'ayat_awal'   => ['required', 'integer', 'min:1'],
            'ayat_akhir'  => ['required', 'integer', 'min:1', 'gte:ayat_awal'],
            'jenis'       => ['required', 'in:ziyadah,murojaah'],
            'kelancaran'  => ['required', 'in:mutqin,lancar,terbata'],
        ], [
            'nomor_surah.required' => 'Pilih surah yang ingin disetor.',
            'nomor_surah.min'      => 'Nomor surah tidak valid.',
            'nomor_surah.max'      => 'Nomor surah tidak valid.',
            'ayat_awal.required'   => 'Nomor ayat awal wajib diisi.',
            'ayat_awal.min'        => 'Nomor ayat minimal 1.',
            'ayat_akhir.required'  => 'Nomor ayat akhir wajib diisi.',
            'ayat_akhir.gte'       => 'Ayat akhir harus lebih besar atau sama dengan ayat awal.',
            'jenis.required'       => 'Pilih jenis setoran.',
            'jenis.in'             => 'Jenis setoran tidak valid.',
            'kelancaran.required'  => 'Pilih tingkat kelancaran.',
            'kelancaran.in'        => 'Tingkat kelancaran tidak valid.',
        ]);

        // Cari nama surah dari API berdasarkan nomor yang dipilih
        // Disimpan langsung ke database agar tidak perlu query API setiap tampil
        $surahList = $this->quranApi->getAllSurah();
        $namaSurah = 'Surah ' . $validated['nomor_surah'];

        foreach ($surahList as $surah) {
            if ($surah['nomor'] == $validated['nomor_surah']) {
                $namaSurah = $surah['namaLatin'];
                break;
            }
        }

        // Simpan setoran ke database
        // Status otomatis 'pending' karena belum dinilai guru
        Hafalan::create([
            'user_id'      => Auth::id(),
            'nomor_surah'  => $validated['nomor_surah'],
            'nama_surah'   => $namaSurah,
            'ayat_awal'    => $validated['ayat_awal'],
            'ayat_akhir'   => $validated['ayat_akhir'],
            'jenis'        => $validated['jenis'],
            'kelancaran'   => $validated['kelancaran'],
            'status'       => 'pending',
            'nilai'        => null,
            'catatan_guru' => null,
            'dinilai_oleh' => null,
        ]);

        return redirect()->route('santri.hafalan.index')
            ->with('success', "Setoran {$namaSurah} ayat {$validated['ayat_awal']}–{$validated['ayat_akhir']} berhasil disimpan. Menunggu penilaian guru.");
    }
}
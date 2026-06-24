<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HafalanController extends Controller
{
    public function index()
    {
        $hafalans = Auth::user()
            ->hafalans()
            ->latest()
            ->paginate(10);

        return view('hafalan.index', compact('hafalans'));
    }

    public function create()
    {
        return view('hafalan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surah' => 'required|integer|min:1|max:114',
            'nama_surah' => 'required|string|max:255',
            'ayat_awal' => 'required|integer|min:1',
            'ayat_akhir' => 'required|integer|min:1',
            'jenis' => 'required|in:ziyadah,murojaah',
        ]);

        Hafalan::create([
            'user_id' => Auth::id(),
            'nomor_surah' => $request->nomor_surah,
            'nama_surah' => $request->nama_surah,
            'ayat_awal' => $request->ayat_awal,
            'ayat_akhir' => $request->ayat_akhir,
            'jenis' => $request->jenis,

            'kelancaran' => 'lancar',
            'status' => 'pending',
        ]);

        return redirect()
            ->route('santri.hafalan.index')
            ->with('success', 'Setoran hafalan berhasil dikirim.');
    }
}
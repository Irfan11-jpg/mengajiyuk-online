<?php

namespace App\Http\Controllers;

use App\Models\IbadahJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalIbadahController extends Controller
{
    /**
     * Tampilkan jurnal hari ini.
     */
    public function index()
    {
        $journal = IbadahJournal::firstOrNew([
            'user_id' => Auth::id(),
            'tanggal' => now()->toDateString(),
        ]);

        return view('santri.jurnal.index', compact('journal'));
    }

    /**
     * Simpan atau update jurnal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        IbadahJournal::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'tanggal' => now()->toDateString(),
            ],
            [
                'subuh' => $request->boolean('subuh'),
                'dzuhur' => $request->boolean('dzuhur'),
                'ashar' => $request->boolean('ashar'),
                'maghrib' => $request->boolean('maghrib'),
                'isya' => $request->boolean('isya'),
                'tilawah' => $request->boolean('tilawah'),
                'murajaah' => $request->boolean('murajaah'),
                'tahajud' => $request->boolean('tahajud'),
                'catatan' => $request->catatan,
            ]
        );

        return redirect()
            ->route('santri.jurnal.index')
            ->with('success', 'Jurnal ibadah berhasil disimpan.');
    }
}
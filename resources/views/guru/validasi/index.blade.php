@extends('layouts.guru')

@section('title', 'Validasi Setoran Hafalan')
@section('page-title', 'Validasi Setoran Hafalan')

@section('content')

{{-- Header --}}
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Antrean Setoran Hafalan</h2>
    <p class="text-gray-500 text-sm mt-1">
        Daftar setoran santri yang menunggu penilaian dari Anda
    </p>
</div>

{{-- Pesan sukses atau error --}}
@if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-5 text-sm flex items-start gap-2">
        <span class="mt-0.5">✅</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm flex items-start gap-2">
        <span class="mt-0.5">⚠️</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- 2 Kartu Statistik --}}
<div class="grid grid-cols-2 gap-4 mb-6">

    <div class="bg-white rounded-xl p-5 shadow-sm border {{ $totalPending > 0 ? 'border-red-200 bg-red-50/20' : 'border-gray-100' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Menunggu Penilaian</p>
                <p class="text-3xl font-bold {{ $totalPending > 0 ? 'text-red-500' : 'text-gray-400' }}">
                    {{ $totalPending }}
                </p>
                <p class="text-xs text-gray-400 mt-1">setoran pending</p>
            </div>
            <span class="text-3xl">⏳</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Dinilai Hari Ini</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $totalDinilaiHariIni }}</p>
                <p class="text-xs text-gray-400 mt-1">oleh Anda</p>
            </div>
            <span class="text-3xl">✅</span>
        </div>
    </div>

</div>

{{-- Tabel Antrean Pending --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">

    <h3 class="font-semibold text-gray-700 mb-4">
        Setoran Menunggu Penilaian
        @if($totalPending > 0)
            <span class="ml-2 text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">
                {{ $totalPending }} antrian
            </span>
        @endif
    </h3>

    @if($setoranPending->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-3 font-medium">Santri</th>
                        <th class="text-left pb-3 font-medium">Surah</th>
                        <th class="text-left pb-3 font-medium">Ayat</th>
                        <th class="text-left pb-3 font-medium">Jenis</th>
                        <th class="text-left pb-3 font-medium">Kelancaran</th>
                        <th class="text-left pb-3 font-medium">Waktu Setor</th>
                        <th class="text-left pb-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($setoranPending as $setoran)
                        <tr class="text-gray-600 hover:bg-amber-50/30 transition-colors">

                            <td class="py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center
                                                justify-center text-sm font-bold text-amber-700 flex-shrink-0">
                                        {{ strtoupper(substr($setoran->santri->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $setoran->santri->name }}</p>
                                        <p class="text-xs text-gray-400">Kelas {{ $setoran->santri->kelas ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3">
                                <p class="font-medium text-gray-800">{{ $setoran->nama_surah }}</p>
                                <p class="text-xs text-gray-400">Surah ke-{{ $setoran->nomor_surah }}</p>
                            </td>

                            <td class="py-3 whitespace-nowrap">
                                Ayat {{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }}
                            </td>

                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $setoran->jenis === 'ziyadah'
                                        ? 'bg-purple-100 text-purple-700'
                                        : 'bg-blue-100 text-blue-700' }}">
                                    {{ $setoran->jenis === 'ziyadah' ? 'Ziyadah' : 'Murojaah' }}
                                </span>
                            </td>

                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $setoran->kelancaran === 'mutqin'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($setoran->kelancaran === 'lancar'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($setoran->kelancaran) }}
                                </span>
                            </td>

                            <td class="py-3 text-xs text-gray-400">
                                <p>{{ $setoran->created_at->format('d M Y') }}</p>
                                <p class="text-gray-300">{{ $setoran->created_at->diffForHumans() }}</p>
                            </td>

                            <td class="py-3">
                                <a href="{{ route('guru.validasi.grade', $setoran->id) }}"
                                   class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600
                                          text-white text-xs font-semibold px-3 py-1.5 rounded-lg
                                          transition-colors whitespace-nowrap">
                                    ✏️ Nilai
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($setoranPending->hasPages())
            <div class="mt-5 pt-4 border-t border-gray-100">
                {{ $setoranPending->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-14 text-gray-400">
            <span class="text-5xl block mb-3">✅</span>
            <p class="text-base font-medium text-gray-600">Semua setoran sudah dinilai</p>
            <p class="text-sm mt-2">Tidak ada antrian setoran yang menunggu saat ini</p>
        </div>
    @endif

</div>

{{-- Setoran yang sudah dinilai hari ini --}}
@if($setoranDinilaiHariIni->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

        <h3 class="font-semibold text-gray-700 mb-4">
            Sudah Dinilai Hari Ini
            <span class="ml-2 text-xs bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full font-medium">
                {{ $setoranDinilaiHariIni->count() }} setoran
            </span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-3 font-medium">Santri</th>
                        <th class="text-left pb-3 font-medium">Surah</th>
                        <th class="text-left pb-3 font-medium">Ayat</th>
                        <th class="text-left pb-3 font-medium">Jenis</th>
                        <th class="text-left pb-3 font-medium">Nilai</th>
                        <th class="text-left pb-3 font-medium">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($setoranDinilaiHariIni as $setoran)
                        <tr class="text-gray-600">
                            <td class="py-3 font-medium text-gray-800">{{ $setoran->santri->name }}</td>
                            <td class="py-3">{{ $setoran->nama_surah }}</td>
                            <td class="py-3">{{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }}</td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $setoran->jenis === 'ziyadah'
                                        ? 'bg-purple-100 text-purple-700'
                                        : 'bg-blue-100 text-blue-700' }}">
                                    {{ $setoran->jenis === 'ziyadah' ? 'Ziyadah' : 'Murojaah' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="text-xl font-bold
                                    {{ $setoran->nilai === 'A' ? 'text-emerald-600'
                                        : ($setoran->nilai === 'B' ? 'text-blue-600'
                                        : ($setoran->nilai === 'C' ? 'text-amber-600'
                                        : 'text-red-600')) }}">
                                    {{ $setoran->nilai }}
                                </span>
                            </td>
                            <td class="py-3 text-xs text-gray-500 max-w-xs">
                                {{ $setoran->catatan_guru ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endif

@endsection
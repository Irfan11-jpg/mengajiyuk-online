@extends('layouts.santri')

@section('title', 'Riwayat Setoran Hafalan')
@section('page-title', 'Tracker Hafalan')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Riwayat Setoran Hafalan</h2>
        <p class="text-gray-500 text-sm mt-1">Semua setoran hafalan yang pernah Anda masukkan</p>
    </div>
    <a href="{{ route('santri.hafalan.create') }}"
       class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white
              text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        + Setor Hafalan Baru
    </a>
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

{{-- 5 Kartu Statistik --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-gray-700">{{ $totalSetoran }}</p>
        <p class="text-xs text-gray-400 mt-1">Total Setoran</p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-emerald-600">{{ $totalApproved }}</p>
        <p class="text-xs text-gray-400 mt-1">Disetujui</p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-amber-500">{{ $totalPending }}</p>
        <p class="text-xs text-gray-400 mt-1">Menunggu</p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-purple-600">{{ $totalZiyadah }}</p>
        <p class="text-xs text-gray-400 mt-1">Ziyadah</p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $totalMurojaah }}</p>
        <p class="text-xs text-gray-400 mt-1">Murojaah</p>
    </div>

</div>

{{-- Tabel Riwayat Setoran --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

    <h3 class="font-semibold text-gray-700 mb-4">Semua Riwayat Setoran</h3>

    @if($hafalans->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-3 font-medium">Surah</th>
                        <th class="text-left pb-3 font-medium">Ayat</th>
                        <th class="text-left pb-3 font-medium">Jenis</th>
                        <th class="text-left pb-3 font-medium">Kelancaran</th>
                        <th class="text-left pb-3 font-medium">Status</th>
                        <th class="text-left pb-3 font-medium">Nilai</th>
                        <th class="text-left pb-3 font-medium">Catatan Guru</th>
                        <th class="text-left pb-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($hafalans as $hafalan)
                        <tr class="text-gray-600 hover:bg-gray-50/50 transition-colors">

                            <td class="py-3">
                                <p class="font-medium text-gray-800">{{ $hafalan->nama_surah }}</p>
                                <p class="text-xs text-gray-400">Surah ke-{{ $hafalan->nomor_surah }}</p>
                            </td>

                            <td class="py-3 whitespace-nowrap">
                                {{ $hafalan->ayat_awal }}–{{ $hafalan->ayat_akhir }}
                            </td>

                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $hafalan->jenis === 'ziyadah'
                                        ? 'bg-purple-100 text-purple-700'
                                        : 'bg-blue-100 text-blue-700' }}">
                                    {{ $hafalan->jenis === 'ziyadah' ? 'Ziyadah' : 'Murojaah' }}
                                </span>
                            </td>

                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $hafalan->kelancaran === 'mutqin'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($hafalan->kelancaran === 'lancar'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($hafalan->kelancaran) }}
                                </span>
                            </td>

                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $hafalan->status === 'approved'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-amber-100 text-amber-700' }}">
                                    {{ $hafalan->status === 'approved' ? 'Disetujui' : 'Menunggu' }}
                                </span>
                            </td>

                            <td class="py-3">
                                @if($hafalan->nilai)
                                    <span class="text-xl font-bold
                                        {{ $hafalan->nilai === 'A' ? 'text-emerald-600'
                                            : ($hafalan->nilai === 'B' ? 'text-blue-600'
                                            : ($hafalan->nilai === 'C' ? 'text-amber-600'
                                            : 'text-red-600')) }}">
                                        {{ $hafalan->nilai }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>

                            <td class="py-3 max-w-xs">
                                @if($hafalan->catatan_guru)
                                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                        {{ $hafalan->catatan_guru }}
                                    </p>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>

                            <td class="py-3 text-xs text-gray-400 whitespace-nowrap">
                                {{ $hafalan->created_at->format('d M Y') }}
                                <p class="text-gray-300">{{ $hafalan->created_at->format('H:i') }}</p>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($hafalans->hasPages())
            <div class="mt-5 pt-4 border-t border-gray-100">
                {{ $hafalans->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-16 text-gray-400">
            <span class="text-5xl block mb-4">📋</span>
            <p class="text-base font-medium text-gray-600">Belum ada riwayat setoran</p>
            <p class="text-sm mt-2 mb-6">Mulai catat hafalan Anda dengan menekan tombol di bawah</p>
            <a href="{{ route('santri.hafalan.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700
                      text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                + Setor Hafalan Pertama Saya
            </a>
        </div>
    @endif

</div>

@endsection
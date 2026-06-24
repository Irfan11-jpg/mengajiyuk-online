@extends('layouts.santri')

@section('title', 'Progres 30 Juz - MengajiYuk')

@section('santri-content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Progres Hafalan 30 Juz</h1>
    <p class="text-gray-500 mt-1">Pantau perkembangan hafalan Anda per juz.</p>
</div>

<!-- Progress Bar Keseluruhan -->
<div class="card mb-8">
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold text-gray-700">Total Progress</p>
        <p class="font-bold text-emerald-600">{{ $progressPersen }}%</p>
    </div>
    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
        <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $progressPersen }}%"></div>
    </div>
    <p class="text-sm text-gray-500 mt-3">{{ $pesanMotivasi }}</p>
</div>

<!-- Grid 30 Juz Besar -->
<div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-6 gap-4">
    @foreach ($juzData as $juz)
        <div
            class="group relative rounded-xl p-4 text-center cursor-pointer transition hover:scale-105
                   {{ $juz['selesai'] ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-gray-100 text-gray-400' }}"
        >
            <p class="text-2xl font-bold">{{ $juz['nomor'] }}</p>
            <p class="text-xs mt-1 truncate">{{ $juz['nama'] }}</p>

            @if ($juz['selesai'])
                <p class="text-[10px] mt-1 opacity-80">{{ $juz['jumlah_setoran'] }}x setoran</p>
            @else
                <p class="text-[10px] mt-1 opacity-70">Belum dihafal</p>
            @endif

            <!-- Tooltip -->
            <div class="absolute z-10 bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block whitespace-nowrap bg-gray-800 text-white text-xs rounded-lg px-3 py-1.5 shadow-lg">
                Juz {{ $juz['nomor'] }} - {{ $juz['nama'] }}
            </div>
        </div>
    @endforeach
</div>

@endsection
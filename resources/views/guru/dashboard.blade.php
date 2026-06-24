@extends('layouts.guru')

@section('title', 'Dashboard Guru - MengajiYuk')

@section('guru-content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Assalamu'alaikum, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 mt-1">Pantau aktivitas hafalan santri Anda.</p>
</div>

<!-- Kartu Statistik -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    <div class="card">
        <p class="text-sm text-gray-500">Total Santri</p>
        <p class="text-3xl font-bold text-amber-700 mt-1">{{ $totalSantri }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Setoran Hari Ini</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $setoranHariIni }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Menunggu Penilaian</p>
        <p class="text-3xl font-bold text-red-500 mt-1">{{ $totalPending }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Rata-rata Progress</p>
        <p class="text-3xl font-bold text-amber-700 mt-1">
            {{ $daftarSantri->count() ? round($daftarSantri->avg('progress_persen')) : 0 }}%
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Bar Chart 7 Hari -->
    <div class="card lg:col-span-2">
        <h2 class="font-semibold text-gray-700 mb-4">Setoran 7 Hari Terakhir</h2>
        <canvas id="chartSetoran" height="220"></canvas>
    </div>

    <!-- Daftar Setoran Pending -->
    <div class="card lg:col-span-1">
        <h2 class="font-semibold text-gray-700 mb-4">Menunggu Penilaian</h2>
        <div class="space-y-3 max-h-72 overflow-y-auto">
            @forelse ($setoranPending as $setoran)
                <div class="flex items-center justify-between border border-gray-100 rounded-lg px-3 py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $setoran->santri->name }}</p>
                        <p class="text-xs text-gray-500">{{ $setoran->nama_surah }} ({{ $setoran->ayat_awal }}-{{ $setoran->ayat_akhir }})</p>
                    </div>
                    <span class="badge-pending">Pending</span>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada setoran yang menunggu.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Tabel Daftar Santri -->
<div class="card">
    <h2 class="font-semibold text-gray-700 mb-4">Daftar Santri</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Total Hafalan</th>
                    <th class="py-2 pr-4 w-64">Progress</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarSantri as $santri)
                    <tr class="border-b border-gray-50">
                        <td class="py-3 pr-4 font-medium text-gray-700">{{ $santri->name }}</td>
                        <td class="py-3 pr-4 text-gray-500">{{ $santri->kelas ?? '-' }}</td>
                        <td class="py-3 pr-4 text-gray-500">{{ $santri->total_hafalan }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-600 rounded-full" style="width: {{ $santri->progress_persen }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-10 text-right">{{ $santri->progress_persen }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-400">Belum ada data santri.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
    import { buatBarChart } from "{{ Vite::asset('resources/js/chart-hafalan.js') }}";

    document.addEventListener('DOMContentLoaded', () => {
        buatBarChart('chartSetoran', @json($chartLabels), @json($chartValues));
    });
</script>
@endpush
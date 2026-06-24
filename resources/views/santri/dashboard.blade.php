@extends('layouts.santri')

@section('title', 'Dashboard Santri - MengajiYuk')

@section('santri-content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Assalamu'alaikum, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 mt-1">Berikut ringkasan progres hafalan Anda.</p>
</div>

<!-- Kartu Statistik -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    <div class="card">
        <p class="text-sm text-gray-500">Total Disetujui</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalApproved }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Ziyadah</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalZiyadah }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Murojaah</p>
        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $totalMurojaah }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Progress Keseluruhan</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $progressPersen }}%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Doughnut Chart -->
    <div class="card lg:col-span-1">
        <h2 class="font-semibold text-gray-700 mb-4">Progress Hafalan</h2>
        <canvas id="chartProgress" height="220"></canvas>
    </div>

    <!-- Mini Grid 30 Juz -->
    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-700">Peta 30 Juz</h2>
            <a href="{{ route('santri.hafalan.progres') }}" class="text-sm text-emerald-600 hover:underline">Lihat detail →</a>
        </div>
        <div class="grid grid-cols-6 sm:grid-cols-10 gap-2">
            @foreach ($juzData as $juz)
                <div
                    title="Juz {{ $juz['nomor'] }} - {{ $juz['nama'] }}"
                    class="aspect-square rounded-lg flex items-center justify-center text-xs font-semibold cursor-default
                           {{ $juz['selesai'] ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400' }}"
                >
                    {{ $juz['nomor'] }}
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Tabel Setoran Terbaru -->
<div class="card">
    <h2 class="font-semibold text-gray-700 mb-4">Setoran Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="py-2 pr-4">Surah</th>
                    <th class="py-2 pr-4">Ayat</th>
                    <th class="py-2 pr-4">Jenis</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($setoranTerbaru as $setoran)
                    <tr class="border-b border-gray-50">
                        <td class="py-3 pr-4 font-medium text-gray-700">{{ $setoran->nama_surah }}</td>
                        <td class="py-3 pr-4 text-gray-500">{{ $setoran->ayat_awal }} - {{ $setoran->ayat_akhir }}</td>
                        <td class="py-3 pr-4 text-gray-500 capitalize">{{ $setoran->jenis }}</td>
                        <td class="py-3 pr-4">
                            <span class="{{ $setoran->status === 'approved' ? 'badge-approved' : 'badge-pending' }}">
                                {{ ucfirst($setoran->status) }}
                            </span>
                        </td>
                        <td class="py-3 pr-4 text-gray-500">{{ $setoran->created_at->translatedFormat('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-400">Belum ada setoran hafalan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
    import { buatDoughnutChart } from "{{ Vite::asset('resources/js/chart-hafalan.js') }}";

    document.addEventListener('DOMContentLoaded', () => {
        buatDoughnutChart('chartProgress', @json($chartLabels), @json($chartValues));
    });
</script>
@endpush
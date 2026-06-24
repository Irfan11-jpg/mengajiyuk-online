@extends('layouts.guru')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Greeting --}}
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">
        Assalamu'alaikum, {{ $guru->name }} 👋
    </h2>
    <p class="text-gray-500 text-sm mt-1">
        Pantau dan kelola perkembangan hafalan santri Anda
    </p>
</div>

{{-- 4 Kartu Statistik --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Total Santri</p>
                <p class="text-2xl font-bold text-amber-600">{{ $totalSantri }}</p>
                <p class="text-xs text-gray-400 mt-1.5">santri terdaftar</p>
            </div>
            <span class="text-2xl">👥</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Setoran Hari Ini</p>
                <p class="text-2xl font-bold text-blue-600">{{ $setoranHariIni }}</p>
                <p class="text-xs text-gray-400 mt-1.5">setoran masuk</p>
            </div>
            <span class="text-2xl">📥</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 {{ $setoranPending > 0 ? 'border-red-200 bg-red-50/30' : '' }}">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Menunggu Penilaian</p>
                <p class="text-2xl font-bold {{ $setoranPending > 0 ? 'text-red-500' : 'text-gray-400' }}">
                    {{ $setoranPending }}
                </p>
                <p class="text-xs text-gray-400 mt-1.5">setoran pending</p>
            </div>
            <span class="text-2xl">⏳</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Modul Aktif</p>
                <p class="text-2xl font-bold text-emerald-600">1/4</p>
                <p class="text-xs text-gray-400 mt-1.5">fitur tersedia</p>
            </div>
            <span class="text-2xl">⚙️</span>
        </div>
    </div>

</div>

{{-- Chart + Setoran Menunggu --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Setoran Hafalan 7 Hari Terakhir</h3>
        <canvas id="chartSetoran" height="220"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Menunggu Penilaian Guru</h3>
            @if($setoranPending > 0)
                <span class="text-xs font-medium bg-red-100 text-red-600 px-2.5 py-1 rounded-full">
                    {{ $setoranPending }} pending
                </span>
            @endif
        </div>
        @if($setoranMenunggu->count() > 0)
            <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
                @foreach($setoranMenunggu as $setoran)
                    <div class="flex items-start justify-between p-3 bg-amber-50 rounded-xl border border-amber-100 hover:border-amber-200 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-gray-800 truncate">{{ $setoran->santri->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $setoran->nama_surah }} ayat {{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ ucfirst($setoran->jenis) }} · {{ ucfirst($setoran->kelancaran) }}
                            </p>
                        </div>
                        <div class="text-right ml-3 flex-shrink-0">
                            <span class="text-xs bg-amber-200 text-amber-700 px-2 py-0.5 rounded-full font-medium">Pending</span>
                            <p class="text-xs text-gray-400 mt-1">{{ $setoran->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($setoranPending > 10)
                <p class="text-xs text-gray-400 text-center mt-3">+ {{ $setoranPending - 10 }} setoran lainnya</p>
            @endif
        @else
            <div class="flex flex-col items-center justify-center py-14 text-gray-400">
                <span class="text-5xl mb-3">✅</span>
                <p class="text-sm font-medium">Semua setoran sudah dinilai</p>
                <p class="text-xs mt-1">Tidak ada antrian yang menunggu</p>
            </div>
        @endif
    </div>

</div>

{{-- Tabel Daftar Santri --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Daftar Santri & Progress Hafalan</h3>
        <span class="text-xs text-gray-400">{{ $totalSantri }} santri</span>
    </div>
    @if($daftarSantri->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-3 font-medium">Nama Santri</th>
                        <th class="text-left pb-3 font-medium">Kelas</th>
                        <th class="text-center pb-3 font-medium">Total Setoran</th>
                        <th class="text-center pb-3 font-medium">Disetujui</th>
                        <th class="text-center pb-3 font-medium">Pending</th>
                        <th class="text-left pb-3 font-medium">Progress Approval</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($daftarSantri as $santri)
                        @php
                            $pct = $santri->total_hafalan > 0
                                ? round(($santri->total_approved / $santri->total_hafalan) * 100)
                                : 0;
                        @endphp
                        <tr class="text-gray-600 hover:bg-gray-50/50 transition-colors">
                            <td class="py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-sm font-bold text-amber-700 flex-shrink-0">
                                        {{ strtoupper(substr($santri->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $santri->name }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                    {{ $santri->kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 text-center font-medium">{{ $santri->total_hafalan }}</td>
                            <td class="py-3 text-center font-semibold text-emerald-600">{{ $santri->total_approved }}</td>
                            <td class="py-3 text-center">
                                @if($santri->total_pending > 0)
                                    <span class="font-semibold text-red-500">{{ $santri->total_pending }}</span>
                                @else
                                    <span class="text-gray-300">0</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-2 min-w-20">
                                        <div class="h-2 rounded-full transition-all duration-500
                                                    {{ $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-blue-500' : ($pct > 0 ? 'bg-amber-400' : 'bg-gray-200')) }}"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-8 text-right">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            <span class="text-4xl block mb-3">👥</span>
            <p class="text-sm font-medium">Belum ada data santri</p>
            <p class="text-xs mt-1">Jalankan seeder untuk menambahkan akun santri demo</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartSetoran');
    if (!ctx) return;
    const labels = @json($labels);
    const data   = @json($data);
    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label:                'Jumlah Setoran',
                data:                 data,
                backgroundColor:      'rgba(217, 119, 6, 0.15)',
                borderColor:          'rgba(217, 119, 6, 0.8)',
                borderWidth:          2,
                borderRadius:         8,
                borderSkipped:        false,
                hoverBackgroundColor: 'rgba(217, 119, 6, 0.3)',
            }]
        },
        options: {
            responsive:          true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ` ${ctx.raw} setoran`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 },
                    grid:  { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
@extends('layouts.santri')

@section('title', 'Dashboard Santri')
@section('page-title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Greeting --}}
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">
        Assalamu'alaikum, {{ $santri->name }} 👋
    </h2>
    <p class="text-gray-500 text-sm mt-1">
        Semangat menghafal hari ini! Kelas {{ $santri->kelas ?? '-' }}
    </p>
</div>

{{-- 4 Kartu Statistik --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Progress Hafalan</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $progressPersen }}%</p>
                <p class="text-xs text-gray-400 mt-1.5">dari 114 surah</p>
            </div>
            <span class="text-2xl">📊</span>
        </div>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $progressPersen }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Surah Disetor</p>
                <p class="text-2xl font-bold text-blue-600">{{ $surahSelesai }}</p>
                <p class="text-xs text-gray-400 mt-1.5">surah unik disetujui</p>
            </div>
            <span class="text-2xl">📖</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Ziyadah</p>
                <p class="text-2xl font-bold text-purple-600">{{ $totalZiyadah }}</p>
                <p class="text-xs text-gray-400 mt-1.5">hafalan baru</p>
            </div>
            <span class="text-2xl">✨</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Murojaah</p>
                <p class="text-2xl font-bold text-amber-600">{{ $totalMurojaah }}</p>
                <p class="text-xs text-gray-400 mt-1.5">pengulangan</p>
            </div>
            <span class="text-2xl">🔄</span>
        </div>
    </div>

</div>

{{-- Chart + Grid 30 Juz --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Chart Doughnut --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Progress Hafalan Surah</h3>
            <a href="{{ route('santri.hafalan.progres') }}" class="text-xs text-emerald-600 hover:underline font-medium">
                Lihat 30 Juz →
            </a>
        </div>
        <div class="mb-5">
            <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                <span>{{ $surahSelesai }} surah selesai</span>
                <span class="font-medium text-emerald-600">{{ $progressPersen }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-3 rounded-full transition-all duration-700"
                     style="width: {{ max($progressPersen, 1) }}%"></div>
            </div>
        </div>
        @if($hafalanPerSurah->count() > 0)
            <div class="flex items-center justify-center py-2">
                <canvas id="chartHafalan" width="240" height="240"></canvas>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <span class="text-5xl mb-3">📖</span>
                <p class="text-sm font-medium">Belum ada hafalan yang disetujui</p>
                <p class="text-xs mt-1 text-center">Setorkan hafalan pertama Anda kepada guru</p>
            </div>
        @endif
    </div>

    {{-- Mini Grid 30 Juz --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">30 Juz Al-Qur'an</h3>
            <div class="flex items-center gap-3 text-xs text-gray-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm bg-emerald-500 inline-block"></span> Ada hafalan
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm bg-gray-200 inline-block"></span> Belum
                </span>
            </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
            @foreach($juzData as $juz => $info)
                <a href="{{ route('santri.hafalan.progres') }}"
                   title="Juz {{ $juz }}"
                   class="flex items-center justify-center rounded-lg text-xs font-bold h-10 transition-all duration-200
                          {{ $info['ada_hafalan']
                              ? 'bg-emerald-500 text-white shadow-sm hover:bg-emerald-600 hover:scale-105'
                              : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                    {{ $juz }}
                </a>
            @endforeach
        </div>
        @php $juzAda = collect($juzData)->where('ada_hafalan', true)->count(); @endphp
        <p class="text-xs text-gray-400 mt-4 text-center">
            {{ $juzAda }} dari 30 juz memiliki hafalan
        </p>
        <a href="{{ route('santri.hafalan.progres') }}"
           class="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2 border border-emerald-300
                  text-emerald-600 text-sm font-medium rounded-lg hover:bg-emerald-50 transition-colors">
            Lihat detail 30 juz →
        </a>
    </div>

</div>

{{-- Tabel Setoran Terbaru --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <h3 class="font-semibold text-gray-700 mb-4">Riwayat Setoran Terbaru</h3>
    @if($setoranTerbaru->count() > 0)
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
                        <th class="text-left pb-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($setoranTerbaru as $setoran)
                        <tr class="text-gray-600 hover:bg-gray-50/50">
                            <td class="py-3">
                                <span class="font-medium text-gray-800">{{ $setoran->nama_surah }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $setoran->nomor_surah }})</span>
                            </td>
                            <td class="py-3">Ayat {{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }}</td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $setoran->jenis === 'ziyadah' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $setoran->jenis === 'ziyadah' ? 'Ziyadah' : 'Murojaah' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $setoran->kelancaran === 'mutqin' ? 'bg-emerald-100 text-emerald-700'
                                        : ($setoran->kelancaran === 'lancar' ? 'bg-blue-100 text-blue-700'
                                        : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($setoran->kelancaran) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $setoran->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $setoran->status === 'approved' ? 'Disetujui' : 'Menunggu' }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($setoran->nilai)
                                    <span class="font-bold text-lg
                                        {{ $setoran->nilai === 'A' ? 'text-emerald-600'
                                            : ($setoran->nilai === 'B' ? 'text-blue-600'
                                            : ($setoran->nilai === 'C' ? 'text-amber-600'
                                            : 'text-red-600')) }}">
                                        {{ $setoran->nilai }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="py-3 text-xs text-gray-400">
                                {{ $setoran->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            <span class="text-4xl block mb-3">📋</span>
            <p class="text-sm font-medium">Belum ada riwayat setoran</p>
            <p class="text-xs mt-1">Setorkan hafalan Anda kepada guru untuk memulai</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
@if($hafalanPerSurah->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartHafalan');
    if (!ctx) return;
    const labels = @json($hafalanPerSurah->pluck('nama_surah'));
    const data   = @json($hafalanPerSurah->pluck('total'));
    const colors = labels.map((_, i) => {
        const baseColors = [
            'rgba(16, 185, 129, 0.85)',
            'rgba(5, 150, 105, 0.85)',
            'rgba(4, 120, 87, 0.85)',
            'rgba(6, 95, 70, 0.85)',
            'rgba(52, 211, 153, 0.85)',
            'rgba(110, 231, 183, 0.85)',
        ];
        return baseColors[i % baseColors.length];
    });
    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data:            data,
                backgroundColor: colors,
                borderColor:     '#ffffff',
                borderWidth:     3,
                hoverOffset:     8,
            }]
        },
        options: {
            responsive:          false,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ` ${ctx.label}: ${ctx.raw} setoran`;
                        }
                    }
                }
            },
            cutout: '60%',
        }
    });
});
</script>
@endif
@endpush
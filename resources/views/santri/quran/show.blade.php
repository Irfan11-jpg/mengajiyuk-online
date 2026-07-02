<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Hafalan') }} — {{ $santri->nama }}
            </h2>
            <a href="{{ route('santri.quran.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                &larr; Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-sm text-gray-500">Juz Terakhir</p>
                    <p class="text-2xl font-semibold text-gray-800">Juz {{ $juzTerakhir }} / 30</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-sm text-gray-500">Total Setoran</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalSetoran }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-sm text-gray-500">Total Murojaah</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalMurojaah }}</p>
                </div>
            </div>

            {{-- Tabel riwayat --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Riwayat Setoran & Murojaah</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Juz</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ayat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($progresses as $progres)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                            {{ $progres->tanggal->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                                            {{ $progres->surah }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                            Juz {{ $progres->juz }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                            {{ $progres->ayat_mulai }} - {{ $progres->ayat_selesai }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $progres->jenis === 'setoran' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ ucfirst($progres->jenis) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $statusColor = match($progres->status) {
                                                    'lancar' => 'bg-green-100 text-green-700',
                                                    'kurang_lancar' => 'bg-yellow-100 text-yellow-700',
                                                    default => 'bg-red-100 text-red-700',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }}">
                                                {{ ucwords(str_replace('_', ' ', $progres->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $progres->catatan }}">
                                            {{ $progres->catatan ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                            Belum ada riwayat hafalan untuk santri ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $progresses->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
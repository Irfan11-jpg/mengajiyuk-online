<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progres Hafalan Al-Qur\'an Santri') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Search --}}
                    <form method="GET" action="{{ route('santri.quran.index') }}" class="mb-6 flex gap-2">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama santri..."
                            class="w-full sm:w-72 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Cari
                        </button>
                        @if($search)
                            <a href="{{ route('santri.quran.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                        @endif
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Juz Terakhir</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Setoran</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progres</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($santris as $santri)
                                    @php
                                        $juz = $santri->quran_progresses_max_juz ?? 0;
                                        $persen = min(100, round(($juz / 30) * 100));
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $santri->nama }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                            Juz {{ $juz }} / 30
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                            {{ $santri->quran_progresses_count }} kali
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="w-40 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $persen }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $persen }}%</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <a href="{{ route('santri.quran.show', $santri) }}"
                                               class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                            Belum ada data santri.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $santris->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
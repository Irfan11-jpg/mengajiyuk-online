@extends('layouts.santri')

@section('title', 'Quran Reader')
@section('page-title', 'Quran Reader')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold">
                    📖 Quran Reader
                </h1>

                <p class="mt-2 text-emerald-100">
                    Baca Al-Qur'an kapan saja dengan tampilan yang nyaman.
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-full bg-white/20 text-5xl">
                🕌
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Total Surah
                    </p>

                    <h2 class="text-3xl font-bold text-emerald-700 mt-2">
                        {{ $totalSurah }}
                    </h2>
                </div>

                <div class="text-5xl">
                    📚
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Makkiyah
                    </p>

                    <h2 class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $surahMakkah }}
                    </h2>
                </div>

                <div class="text-5xl">
                    🕋
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Madaniyah
                    </p>

                    <h2 class="text-3xl font-bold text-purple-600 mt-2">
                        {{ $surahMadinah }}
                    </h2>
                </div>

                <div class="text-5xl">
                    🌙
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Total Ayat
                    </p>

                    <h2 class="text-3xl font-bold text-orange-500 mt-2">
                        {{ collect($surahList)->sum('jumlahAyat') }}
                    </h2>
                </div>

                <div class="text-5xl">
                    📜
                </div>
            </div>
        </div>

    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl shadow p-6">

        <label class="block text-sm font-semibold text-gray-700 mb-3">
            Cari Surah
        </label>

        <input
            id="searchSurah"
            type="text"
            placeholder="Contoh : Al Fatihah, Yasin, Al Mulk..."
            class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
        >

    </div>

    {{-- Daftar Surah --}}
    <div
        id="surahContainer"
        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
    >@forelse ($surahList as $surah)

<div
    class="surah-card bg-white rounded-2xl shadow hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-5 text-white">

        <div class="flex justify-between items-center">

            <div>

                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                    Surah {{ $surah['nomor'] }}
                </span>

                <h2 class="text-xl font-bold mt-3 surah-name">
                    {{ $surah['namaLatin'] }}
                </h2>

                <p class="text-emerald-100 text-sm">
                    {{ $surah['arti'] }}
                </p>

            </div>

            <div class="text-right">

                <div class="text-4xl font-arabic leading-none">
                    {{ $surah['nama'] }}
                </div>

            </div>

        </div>

    </div>

    {{-- Body --}}
    <div class="p-5">

        <div class="flex items-center justify-between mb-4">

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold

                @if($surah['tempatTurun']=='Mekah')
                    bg-blue-100 text-blue-700
                @else
                    bg-purple-100 text-purple-700
                @endif">

                {{ $surah['tempatTurun'] }}

            </span>

            <span class="text-sm text-gray-500">

                {{ $surah['jumlahAyat'] }} Ayat

            </span>

        </div>

        <div class="space-y-2 text-sm text-gray-600">

            <div class="flex justify-between">

                <span>Nomor Surah</span>

                <span class="font-semibold">
                    {{ $surah['nomor'] }}
                </span>

            </div>

            <div class="flex justify-between">

                <span>Turun</span>

                <span class="font-semibold">

                    {{ $surah['tempatTurun'] }}

                </span>

            </div>

        </div>

        <div class="mt-6">

            <a href="{{ route('santri.quran.show',$surah['nomor']) }}"
               class="w-full inline-flex justify-center items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-xl font-semibold transition">

                📖 Baca Surah

            </a>

        </div>

    </div>

</div>

@empty

<div class="col-span-3">

    <div class="bg-white rounded-xl shadow p-10 text-center">

        <div class="text-6xl mb-4">

            📖

        </div>

        <h2 class="text-xl font-bold text-gray-700">

            Data Surah Tidak Ditemukan

        </h2>

        <p class="text-gray-500 mt-2">

            Pastikan koneksi internet tersedia.

        </p>

    </div>

</div>

@endforelse

    </div>

    {{-- Tidak ditemukan --}}
    <div id="notFound"
         class="hidden bg-white rounded-2xl shadow p-10 text-center">

        <div class="text-6xl mb-4">
            😔
        </div>

        <h2 class="text-xl font-bold text-gray-700">
            Surah Tidak Ditemukan
        </h2>

        <p class="text-gray-500 mt-2">
            Coba gunakan kata kunci lain.
        </p>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const search = document.getElementById('searchSurah');
    const cards = document.querySelectorAll('.surah-card');
    const empty = document.getElementById('notFound');

    search.addEventListener('keyup', function () {

        let keyword = this.value.toLowerCase().trim();
        let totalVisible = 0;

        cards.forEach(card => {

            let surah = card.querySelector('.surah-name').innerText.toLowerCase();

            if (surah.includes(keyword)) {
                card.style.display = "block";
                totalVisible++;
            } else {
                card.style.display = "none";
            }

        });

        if(totalVisible === 0){
            empty.classList.remove('hidden');
        }else{
            empty.classList.add('hidden');
        }

    });

});
</script>

@endsection
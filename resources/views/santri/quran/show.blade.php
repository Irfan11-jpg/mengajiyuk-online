@extends('layouts.santri')

@section('title', $surah['namaLatin'])
@section('page-title', 'Quran Reader')

@section('content')

<div class="space-y-6">

    {{-- Tombol Kembali --}}
    <div>
        <a href="{{ route('santri.quran.index') }}"
           class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
            ← Kembali ke Daftar Surah
        </a>
    </div>

    {{-- Header Surah --}}
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl shadow-lg p-8 text-white">

        <div class="text-center">

            <p class="text-6xl mb-4">
                {{ $surah['nama'] }}
            </p>

            <h1 class="text-3xl font-bold">
                {{ $surah['namaLatin'] }}
            </h1>

            <p class="text-emerald-100 mt-2">
                {{ $surah['arti'] }}
            </p>

            <div class="mt-6 flex justify-center gap-3 flex-wrap">

                <span class="bg-white/20 px-4 py-2 rounded-full">
                    {{ $surah['jumlahAyat'] }} Ayat
                </span>

                <span class="bg-white/20 px-4 py-2 rounded-full">
                    {{ $surah['tempatTurun'] }}
                </span>

            </div>

        </div>

    </div>

    {{-- Audio --}}
    @if(isset($surah['audioFull']))
    <div class="bg-white rounded-2xl shadow p-6">

        <h2 class="font-bold text-lg mb-4">
            🎧 Murottal Surah
        </h2>

        <audio controls class="w-full">

            <source src="{{ is_array($surah['audioFull']) ? ($surah['audioFull']['05'] ?? reset($surah['audioFull'])) : $surah['audioFull'] }}">

            Browser Anda tidak mendukung audio.

        </audio>

    </div>
    @endif

    {{-- Bismillah --}}
    @if($surah['nomor'] != 9)

    <div class="bg-white rounded-2xl shadow p-8 text-center">

        <p class="text-5xl leading-loose">

            ﷽

        </p>

    </div>

    @endif

    {{-- Daftar Ayat --}}
    <div class="space-y-5">

@foreach($surah['ayat'] as $ayat)

<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-5">

        <span class="bg-emerald-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">

            {{ $ayat['nomorAyat'] }}

        </span>

    </div>

    <p class="text-right text-4xl leading-loose mb-8">

        {{ $ayat['teksArab'] }}

    </p>

    <p class="italic text-emerald-700 mb-4">

        {{ $ayat['teksLatin'] }}

    </p>

    <p class="text-gray-700 leading-8">

        {{ $ayat['teksIndonesia'] }}

    </p>

</div>

@endforeach

    </div>    
    {{-- Navigasi Surah --}}
    <div class="flex justify-between items-center mt-8">

        {{-- Surah Sebelumnya --}}
        <div>
            @if($surahSebelumnya)
                <a href="{{ route('santri.quran.show', $surahSebelumnya['nomor']) }}"
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3 rounded-xl transition">
                    ⬅️
                    <div class="text-left">
                        <div class="text-xs text-gray-500">Surah Sebelumnya</div>
                        <div class="font-semibold">{{ $surahSebelumnya['namaLatin'] }}</div>
                    </div>
                </a>
            @endif
        </div>

        {{-- Kembali --}}
        <div>
            <a href="{{ route('santri.quran.index') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                📖 Daftar Surah
            </a>
        </div>

        {{-- Surah Berikutnya --}}
        <div>
            @if($surahBerikutnya)
                <a href="{{ route('santri.quran.show', $surahBerikutnya['nomor']) }}"
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3 rounded-xl transition">
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Surah Berikutnya</div>
                        <div class="font-semibold">{{ $surahBerikutnya['namaLatin'] }}</div>
                    </div>
                    ➡️
                </a>
            @endif
        </div>

    </div>

</div>

@endsection
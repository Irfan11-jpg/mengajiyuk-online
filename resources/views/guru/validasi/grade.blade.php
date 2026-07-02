@extends('layouts.guru')

@section('title', 'Nilai Setoran Hafalan')
@section('page-title', 'Penilaian Setoran Hafalan')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6">
    <p class="text-sm text-gray-400 flex items-center gap-1.5">
        <a href="{{ route('guru.validasi.index') }}" class="hover:text-amber-600 transition-colors">
            Validasi Setoran
        </a>
        <span>›</span>
        <span class="text-gray-600 font-medium">Form Penilaian</span>
    </p>
    <h2 class="text-xl font-bold text-gray-800 mt-2">Form Penilaian Setoran</h2>
</div>

<div class="max-w-2xl">

    {{-- Info Setoran yang Dinilai --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">

        <h3 class="font-semibold text-amber-800 mb-4 flex items-center gap-2">
            <span>📋</span> Detail Setoran yang Dinilai
        </h3>

        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>
                <p class="text-xs text-amber-600 font-medium mb-0.5">Nama Santri</p>
                <p class="font-semibold text-gray-800">{{ $hafalan->santri->name }}</p>
            </div>

            <div>
                <p class="text-xs text-amber-600 font-medium mb-0.5">Kelas</p>
                <p class="font-semibold text-gray-800">{{ $hafalan->santri->kelas ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-amber-600 font-medium mb-0.5">Surah yang Disetor</p>
                <p class="font-semibold text-gray-800">{{ $hafalan->nama_surah }}</p>
                <p class="text-xs text-gray-400">Surah ke-{{ $hafalan->nomor_surah }}</p>
            </div>

            <div>
                <p class="text-xs text-amber-600 font-medium mb-0.5">Rentang Ayat</p>
                <p class="font-semibold text-gray-800">
                    Ayat {{ $hafalan->ayat_awal }} — {{ $hafalan->ayat_akhir }}
                </p>
            </div>

            <div>
                <p class="text-xs text-amber-600 font-medium mb-0.5">Jenis Setoran</p>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $hafalan->jenis === 'ziyadah'
                        ? 'bg-purple-100 text-purple-700'
                        : 'bg-blue-100 text-blue-700' }}">
                    {{ $hafalan->jenis === 'ziyadah' ? 'Ziyadah (Hafalan Baru)' : 'Murojaah (Pengulangan)' }}
                </span>
            </div>

            <div>
                <p class="text-xs text-amber-600 font-medium mb-0.5">Kelancaran (Laporan Santri)</p>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $hafalan->kelancaran === 'mutqin'
                        ? 'bg-emerald-100 text-emerald-700'
                        : ($hafalan->kelancaran === 'lancar'
                            ? 'bg-blue-100 text-blue-700'
                            : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($hafalan->kelancaran) }}
                </span>
            </div>

            <div class="col-span-2">
                <p class="text-xs text-amber-600 font-medium mb-0.5">Waktu Setor</p>
                <p class="text-sm text-gray-700">
                    {{ $hafalan->created_at->format('d F Y, H:i') }}
                    <span class="text-gray-400 ml-1">({{ $hafalan->created_at->diffForHumans() }})</span>
                </p>
            </div>

        </div>
    </div>

    {{-- Form Penilaian --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <h3 class="font-semibold text-gray-700 mb-5">Berikan Penilaian</h3>

        <form method="POST" action="{{ route('guru.validasi.approve', $hafalan->id) }}">
            @csrf

            {{-- PILIH NILAI --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nilai Hafalan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-4 gap-3">

                    <label class="relative cursor-pointer">
                        <input type="radio" name="nilai" value="A" class="peer sr-only"
                            {{ old('nilai') === 'A' ? 'checked' : '' }} required>
                        <div class="border-2 rounded-xl p-4 text-center transition-all cursor-pointer
                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-3xl font-bold text-emerald-600 block mb-1">A</span>
                            <span class="text-xs text-gray-500 block">Sangat Baik</span>
                            <span class="text-xs text-emerald-500 block mt-0.5">Mutqin</span>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="nilai" value="B" class="peer sr-only"
                            {{ old('nilai') === 'B' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition-all cursor-pointer
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-3xl font-bold text-blue-600 block mb-1">B</span>
                            <span class="text-xs text-gray-500 block">Baik</span>
                            <span class="text-xs text-blue-500 block mt-0.5">Lancar</span>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="nilai" value="C" class="peer sr-only"
                            {{ old('nilai') === 'C' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition-all cursor-pointer
                                    peer-checked:border-amber-500 peer-checked:bg-amber-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-3xl font-bold text-amber-600 block mb-1">C</span>
                            <span class="text-xs text-gray-500 block">Cukup</span>
                            <span class="text-xs text-amber-500 block mt-0.5">Perlu latihan</span>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="nilai" value="D" class="peer sr-only"
                            {{ old('nilai') === 'D' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition-all cursor-pointer
                                    peer-checked:border-red-400 peer-checked:bg-red-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-3xl font-bold text-red-500 block mb-1">D</span>
                            <span class="text-xs text-gray-500 block">Kurang</span>
                            <span class="text-xs text-red-400 block mt-0.5">Ulangi lagi</span>
                        </div>
                    </label>

                </div>
                @error('nilai')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- CATATAN GURU (OPSIONAL) --}}
            <div class="mb-8">
                <label for="catatan_guru" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Catatan Evaluasi
                    <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea
                    id="catatan_guru"
                    name="catatan_guru"
                    rows="4"
                    maxlength="500"
                    placeholder="Contoh: Perbaiki panjang pendek di ayat 5, makhraj huruf ain perlu diperhatikan..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800
                           placeholder-gray-400 focus:outline-none focus:ring-2
                           focus:ring-amber-500 focus:border-transparent transition resize-none
                           {{ $errors->has('catatan_guru') ? 'border-red-400 bg-red-50' : '' }}"
                >{{ old('catatan_guru') }}</textarea>
                <div class="flex justify-between mt-1">
                    @error('catatan_guru')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-400">Catatan ini akan terlihat oleh santri</p>
                    @enderror
                    <p class="text-xs text-gray-400">Maks. 500 karakter</p>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="flex-1 bg-amber-500 hover:bg-amber-600 active:bg-amber-700
                           text-white font-semibold py-3 px-6 rounded-xl transition-colors
                           focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    ✅ Setujui & Simpan Penilaian
                </button>
                <a href="{{ route('guru.validasi.index') }}"
                   class="px-5 py-3 border border-gray-300 text-gray-600 hover:border-gray-400
                          hover:text-gray-800 font-medium rounded-xl transition-colors text-sm whitespace-nowrap">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
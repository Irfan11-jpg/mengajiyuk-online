@extends('layouts.santri')

@section('title', 'Setor Hafalan Baru')
@section('page-title', 'Setor Hafalan Baru')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6">
    <p class="text-sm text-gray-400 flex items-center gap-1.5">
        <a href="{{ route('santri.hafalan.index') }}" class="hover:text-emerald-600 transition-colors">
            Riwayat Hafalan
        </a>
        <span>›</span>
        <span class="text-gray-600 font-medium">Setor Hafalan Baru</span>
    </p>
    <h2 class="text-xl font-bold text-gray-800 mt-2">Form Setoran Hafalan</h2>
    <p class="text-gray-500 text-sm mt-1">
        Isi form di bawah untuk melaporkan hafalan yang ingin Anda setorkan kepada guru
    </p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form method="POST" action="{{ route('santri.hafalan.store') }}">
            @csrf

            {{-- PILIH SURAH --}}
            <div class="mb-6">
                <label for="nomor_surah" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Pilih Surah <span class="text-red-500">*</span>
                </label>
                <select
                    id="nomor_surah"
                    name="nomor_surah"
                    required
                    class="w-full px-4 py-2.5 border rounded-xl text-sm text-gray-800
                           focus:outline-none focus:ring-2 focus:ring-emerald-500
                           focus:border-transparent transition cursor-pointer
                           {{ $errors->has('nomor_surah') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled selected>-- Pilih surah yang ingin disetor --</option>
                    @foreach($surahList as $surah)
                        <option
                            value="{{ $surah['nomor'] }}"
                            {{ old('nomor_surah') == $surah['nomor'] ? 'selected' : '' }}
                        >
                            {{ $surah['nomor'] }}. {{ $surah['namaLatin'] }} ({{ $surah['nama'] }}) — {{ $surah['jumlahAyat'] }} ayat
                        </option>
                    @endforeach
                </select>
                @error('nomor_surah')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @if(count($surahList) === 0)
                    <p class="mt-1.5 text-xs text-amber-600">
                        ⚠️ Daftar surah tidak dapat dimuat. Pastikan koneksi internet aktif lalu refresh halaman.
                    </p>
                @endif
            </div>

            {{-- RENTANG AYAT --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="ayat_awal" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ayat Awal <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="ayat_awal"
                        name="ayat_awal"
                        value="{{ old('ayat_awal') }}"
                        min="1"
                        placeholder="Contoh: 1"
                        required
                        class="w-full px-4 py-2.5 border rounded-xl text-sm text-gray-800
                               placeholder-gray-400 focus:outline-none focus:ring-2
                               focus:ring-emerald-500 focus:border-transparent transition
                               {{ $errors->has('ayat_awal') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('ayat_awal')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ayat_akhir" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ayat Akhir <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="ayat_akhir"
                        name="ayat_akhir"
                        value="{{ old('ayat_akhir') }}"
                        min="1"
                        placeholder="Contoh: 10"
                        required
                        class="w-full px-4 py-2.5 border rounded-xl text-sm text-gray-800
                               placeholder-gray-400 focus:outline-none focus:ring-2
                               focus:ring-emerald-500 focus:border-transparent transition
                               {{ $errors->has('ayat_akhir') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('ayat_akhir')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- JENIS SETORAN --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Setoran <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="jenis" value="ziyadah" class="peer sr-only"
                            {{ old('jenis', 'ziyadah') === 'ziyadah' ? 'checked' : '' }} required>
                        <div class="border-2 rounded-xl p-4 transition-all cursor-pointer
                                    peer-checked:border-purple-500 peer-checked:bg-purple-50
                                    border-gray-200 hover:border-gray-300">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg">✨</span>
                                <span class="font-semibold text-sm text-gray-800">Ziyadah</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Hafalan baru yang belum pernah disetor sebelumnya
                            </p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="jenis" value="murojaah" class="peer sr-only"
                            {{ old('jenis') === 'murojaah' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 transition-all cursor-pointer
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    border-gray-200 hover:border-gray-300">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg">🔄</span>
                                <span class="font-semibold text-sm text-gray-800">Murojaah</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Pengulangan hafalan yang sudah pernah disetor sebelumnya
                            </p>
                        </div>
                    </label>
                </div>
                @error('jenis')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- TINGKAT KELANCARAN --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tingkat Kelancaran <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="kelancaran" value="mutqin" class="peer sr-only"
                            {{ old('kelancaran') === 'mutqin' ? 'checked' : '' }} required>
                        <div class="border-2 rounded-xl p-3 text-center transition-all cursor-pointer
                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-xl block mb-1">🌟</span>
                            <span class="font-semibold text-xs text-gray-800 block">Mutqin</span>
                            <span class="text-xs text-gray-400">Sangat lancar</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="kelancaran" value="lancar" class="peer sr-only"
                            {{ old('kelancaran', 'lancar') === 'lancar' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-3 text-center transition-all cursor-pointer
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-xl block mb-1">👍</span>
                            <span class="font-semibold text-xs text-gray-800 block">Lancar</span>
                            <span class="text-xs text-gray-400">Ada sedikit kesalahan</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="kelancaran" value="terbata" class="peer sr-only"
                            {{ old('kelancaran') === 'terbata' ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-3 text-center transition-all cursor-pointer
                                    peer-checked:border-red-400 peer-checked:bg-red-50
                                    border-gray-200 hover:border-gray-300">
                            <span class="text-xl block mb-1">📚</span>
                            <span class="font-semibold text-xs text-gray-800 block">Terbata</span>
                            <span class="text-xs text-gray-400">Perlu banyak latihan</span>
                        </div>
                    </label>
                </div>
                @error('kelancaran')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800
                           text-white font-semibold py-3 px-6 rounded-xl transition-colors
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    📤 Setor Hafalan
                </button>
                <a href="{{ route('santri.hafalan.index') }}"
                   class="px-5 py-3 border border-gray-300 text-gray-600 hover:border-gray-400
                          hover:text-gray-800 font-medium rounded-xl transition-colors text-sm">
                    Batal
                </a>
            </div>

        </form>

    </div>

    {{-- Info tambahan --}}
    <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4">
        <p class="text-xs text-blue-700 font-medium mb-1">💡 Informasi</p>
        <p class="text-xs text-blue-600 leading-relaxed">
            Setoran yang Anda masukkan akan berstatus <strong>Menunggu</strong> sampai guru
            mendengarkan dan memberikan penilaian. Status akan berubah menjadi
            <strong>Disetujui</strong> setelah guru menilai.
        </p>
    </div>

</div>

@endsection
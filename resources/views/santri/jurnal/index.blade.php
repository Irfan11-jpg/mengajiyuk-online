@extends('layouts.santri')

@section('title', 'Jurnal Ibadah')
@section('page-title', 'Jurnal Ibadah')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h2 class="text-xl font-bold text-gray-800">
                    📜 Jurnal Ibadah Harian
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Catat ibadah harian agar progres ibadah dapat dipantau.
                </p>

            </div>

            <div class="text-right">

                <p class="text-sm text-gray-500">Tanggal</p>

                <p class="font-semibold text-emerald-600">
                    {{ now()->translatedFormat('d F Y') }}
                </p>

            </div>

        </div>

        <form action="{{ route('santri.jurnal.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                @foreach([
                    'subuh'=>'Subuh',
                    'dzuhur'=>'Dzuhur',
                    'ashar'=>'Ashar',
                    'maghrib'=>'Maghrib',
                    'isya'=>'Isya',
                    'tilawah'=>'Tilawah',
                    'murajaah'=>'Murajaah',
                    'tahajud'=>'Tahajud'
                ] as $field=>$label)

                    <label class="flex items-center gap-3 p-4 border rounded-xl hover:bg-emerald-50 cursor-pointer">

                        <input
                            type="checkbox"
                            name="{{ $field }}"
                            class="w-5 h-5 text-emerald-600 rounded"
                            {{ $journal->$field ? 'checked' : '' }}
                        >

                        <span>{{ $label }}</span>

                    </label>

                @endforeach

            </div>

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan
                </label>

                <textarea
                    name="catatan"
                    rows="5"
                    class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="Tulis catatan ibadah hari ini...">{{ old('catatan',$journal->catatan) }}</textarea>

            </div>

            <div class="mt-6">

                <button
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold">

                    Simpan Jurnal santri

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
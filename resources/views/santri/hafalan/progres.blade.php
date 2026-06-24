@extends('layouts.santri')

@section('title', 'Progres 30 Juz')
@section('page-title', 'Progres Hafalan 30 Juz')

@section('content')

{{-- Header Progress --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Visualisasi Hafalan 30 Juz Al-Qur'an</h2>
            <p class="text-sm text-gray-400 mt-1">
                {{ $surahSelesai }} dari 114 surah telah disetor dan disetujui guru
            </p>
        </div>
        <div class="text-center bg-emerald-50 rounded-2xl px-6 py-3 border border-emerald-100">
            <p class="text-4xl font-bold text-emerald-600">{{ $progressPersen }}%</p>
            <p class="text-xs text-gray-400 mt-0.5">Total progress hafalan</p>
        </div>
    </div>
    <div class="mt-5">
        <div class="flex justify-between text-xs text-gray-400 mb-1.5">
            <span>0%</span>
            <span>50%</span>
            <span>100%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-5 relative">
            <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-5 rounded-full transition-all duration-1000 flex items-center justify-end pr-2"
                 style="width: {{ max($progressPersen, 2) }}%">
                @if($progressPersen > 8)
                    <span class="text-white text-xs font-semibold">{{ $progressPersen }}%</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Legenda --}}
<div class="flex flex-wrap items-center gap-5 mb-4 px-1">
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <span class="w-6 h-6 rounded-lg bg-emerald-500 inline-block shadow-sm"></span>
        Ada hafalan yang disetujui guru
    </div>
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <span class="w-6 h-6 rounded-lg bg-gray-200 inline-block"></span>
        Belum ada hafalan
    </div>
</div>

{{-- Grid 30 Juz --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="font-semibold text-gray-700 mb-4">Peta 30 Juz</h3>
    <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-10 gap-3">
        @foreach($juzData as $juz => $info)
            <div class="relative group">
                <div class="flex flex-col items-center justify-center rounded-xl text-center h-16 transition-all duration-200 cursor-default select-none
                            {{ $info['ada_hafalan']
                                ? 'bg-emerald-500 text-white shadow-md hover:bg-emerald-600 hover:scale-105 hover:shadow-lg'
                                : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                    <span class="text-lg font-bold">{{ $juz }}</span>
                    <span class="text-xs opacity-75">Juz</span>
                    @if($info['ada_hafalan'])
                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-emerald-700 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                            <span class="text-white text-xs font-bold">✓</span>
                        </span>
                    @endif
                </div>
                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-20 pointer-events-none">
                    <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl">
                        <p class="font-semibold">Juz {{ $juz }}</p>
                        <p class="text-gray-300">Surah {{ $info['start_surah'] }}–{{ $info['end_surah'] }}</p>
                        <p class="text-xs mt-0.5 {{ $info['ada_hafalan'] ? 'text-emerald-400' : 'text-gray-400' }}">
                            {{ $info['ada_hafalan'] ? '✓ Ada hafalan' : '— Belum ada hafalan' }}
                        </p>
                    </div>
                    <div class="w-0 h-0 mx-auto border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-gray-900"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- 3 Kartu Statistik --}}
@php
    $juzAdaHafalan = collect($juzData)->where('ada_hafalan', true)->count();
    $juzBelum      = 30 - $juzAdaHafalan;
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-emerald-600 mb-1">{{ $juzAdaHafalan }}</p>
        <p class="text-sm font-medium text-gray-700">Juz dengan hafalan</p>
        <p class="text-xs text-gray-400 mt-0.5">dari 30 juz Al-Qur'an</p>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ round(($juzAdaHafalan / 30) * 100) }}%"></div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-blue-600 mb-1">{{ $surahSelesai }}</p>
        <p class="text-sm font-medium text-gray-700">Surah disetor</p>
        <p class="text-xs text-gray-400 mt-0.5">dari 114 surah total</p>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $progressPersen }}%"></div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-amber-600 mb-1">{{ $juzBelum }}</p>
        <p class="text-sm font-medium text-gray-700">Juz belum dimulai</p>
        <p class="text-xs text-gray-400 mt-0.5">sisa untuk diselesaikan</p>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ round(($juzBelum / 30) * 100) }}%"></div>
        </div>
    </div>
</div>

{{-- Pesan Motivasi --}}
@if($progressPersen == 0)
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-6 text-center">
        <p class="text-4xl mb-3">🌱</p>
        <p class="font-bold text-emerald-700 text-lg">Bismillah, mulai perjalanan hafalan Anda!</p>
        <p class="text-sm text-emerald-600 mt-2">Setiap hafizh Al-Qur'an dimulai dari satu ayat pertama. Semangat!</p>
    </div>
@elseif($progressPersen < 25)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 text-center">
        <p class="text-4xl mb-3">🚀</p>
        <p class="font-bold text-blue-700 text-lg">Awal yang luar biasa!</p>
        <p class="text-sm text-blue-600 mt-2">Langkah pertama adalah yang terberat. Anda sudah melewatinya. Terus semangat!</p>
    </div>
@elseif($progressPersen < 50)
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-2xl p-6 text-center">
        <p class="text-4xl mb-3">💪</p>
        <p class="font-bold text-purple-700 text-lg">Progres yang bagus!</p>
        <p class="text-sm text-purple-600 mt-2">Konsistensi adalah kunci. Terus pertahankan semangat menghafal Anda!</p>
    </div>
@elseif($progressPersen < 75)
    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl p-6 text-center">
        <p class="text-4xl mb-3">⭐</p>
        <p class="font-bold text-amber-700 text-lg">Masya Allah! Sudah lebih dari separuh!</p>
        <p class="text-sm text-amber-600 mt-2">Anda sudah melewati lebih dari separuh perjalanan. Jangan berhenti!</p>
    </div>
@else
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-300 rounded-2xl p-6 text-center">
        <p class="text-4xl mb-3">🏆</p>
        <p class="font-bold text-yellow-700 text-lg">Masya Allah Tabarakallah!</p>
        <p class="text-sm text-yellow-600 mt-2">Hampir khatam 30 juz! Semoga Allah mudahkan dan berkahi hafalan Anda.</p>
    </div>
@endif

@endsection
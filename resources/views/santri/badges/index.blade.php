@extends('layouts.santri')

@section('title', 'Badge & Streak')
@section('page-title', 'Badge & Streak')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">
            🏅 Badge & Streak
        </h2>

        <p class="text-gray-500 mt-2">
            Kumpulkan badge dengan menjaga konsistensi ibadah harian.
        </p>
    </div>

    {{-- Statistik --}}
    <div class="grid md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <div class="text-4xl mb-3">
                🔥
            </div>

            <h3 class="text-gray-500">
                Current Streak
            </h3>

            <p class="text-4xl font-bold text-orange-500 mt-2">
                {{ auth()->user()->currentStreak() }}
            </p>

            <p class="text-sm text-gray-400 mt-2">
                Hari berturut-turut
            </p>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <div class="text-4xl mb-3">
                🏅
            </div>

            <h3 class="text-gray-500">
                Total Badge
            </h3>

            <p class="text-4xl font-bold text-emerald-600 mt-2">
                {{ $badges->count() }}
            </p>

            <p class="text-sm text-gray-400 mt-2">
                Badge tersedia
            </p>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <div class="text-4xl mb-3">
                ⭐
            </div>

            <h3 class="text-gray-500">
                Best Streak
            </h3>

            <p class="text-4xl font-bold text-blue-600 mt-2">
                {{ auth()->user()->best_streak ?? 0 }}
            </p>

            <p class="text-sm text-gray-400 mt-2">
                Rekor terbaik
            </p>

        </div>

    </div>

    {{-- Badge --}}
    <div class="bg-white rounded-2xl shadow-sm border p-8">

        <h3 class="text-xl font-bold mb-6">
            Koleksi Badge
        </h3>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($badges as $badge)

                @php

                    $dimiliki = auth()->user()
                        ->userBadges
                        ->contains('badge_id', $badge->id);

                @endphp

                <div
                    class="rounded-2xl border p-6 text-center transition hover:shadow-lg
                    {{ $dimiliki
                        ? 'border-emerald-400 bg-emerald-50'
                        : 'border-gray-200 bg-gray-50 opacity-70' }}">

                    <div class="text-6xl mb-4">

                        {{ $dimiliki ? $badge->icon : '🔒' }}

                    </div>

                    <h4 class="font-bold text-lg">

                        {{ $badge->nama }}

                    </h4>

                    <p class="text-sm text-gray-500 mt-2">

                        {{ $badge->deskripsi }}

                    </p>

                    <div class="mt-4">

                        @if($dimiliki)

                            <span class="px-3 py-1 rounded-full bg-emerald-600 text-white text-xs">

                                Dimiliki

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-gray-300 text-gray-700 text-xs">

                                Belum Didapat

                            </span>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection
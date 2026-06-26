<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Santri') — MengajiYuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">

<div class="flex min-h-screen">

    {{-- SIDEBAR SANTRI --}}
    <aside class="w-64 bg-emerald-800 text-white flex flex-col flex-shrink-0">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-emerald-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-xl">🕋</span>
                </div>
                <div>
                    <p class="font-bold text-base leading-tight">MengajiYuk</p>
                    <p class="text-emerald-300 text-xs">Portal Santri</p>
                </div>
            </div>
        </div>

        {{-- Info santri yang login --}}
        <div class="px-6 py-4 border-b border-emerald-700 bg-emerald-900/30">
            <p class="text-xs text-emerald-400 mb-0.5">Selamat datang,</p>
            <p class="font-semibold text-sm truncate">{{ Auth::user()->name }}</p>
            <span class="inline-block mt-1.5 text-xs bg-emerald-600/50 text-emerald-200 px-2.5 py-0.5 rounded-full border border-emerald-600">
                Kelas {{ Auth::user()->kelas ?? '-' }}
            </span>
        </div>

        {{-- Menu navigasi --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <p class="text-emerald-400 text-xs font-medium uppercase tracking-wider px-3 mb-2 mt-1">
                Menu Utama
            </p>

            <a href="{{ route('santri.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('santri.dashboard')
                          ? 'bg-emerald-600 text-white font-medium'
                          : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                <span class="text-base">🏠</span>
                <span>Dashboard</span>
            </a>

                {{-- DENGAN ini (mengarah ke index riwayat): --}}
            <a href="{{ route('santri.hafalan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('santri.hafalan.*')
                          ? 'bg-emerald-600 text-white font-medium'
                          : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                <span class="text-base">📖</span>
                <span>Tracker Hafalan</span>
            </a>
            
            <div class="pt-3 pb-1">
                <p class="text-emerald-400 text-xs font-medium uppercase tracking-wider px-3 mb-2">
                    Segera Hadir
                </p>
            </div>

            <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-emerald-400 opacity-50 cursor-not-allowed">
                <span class="text-base">📜</span>
                <span>Jurnal Ibadah</span>
                <span class="ml-auto text-xs bg-emerald-900 text-emerald-500 px-1.5 py-0.5 rounded">Soon</span>
            </span>

            <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-emerald-400 opacity-50 cursor-not-allowed">
                <span class="text-base">🏅</span>
                <span>Badge & Streak</span>
                <span class="ml-auto text-xs bg-emerald-900 text-emerald-500 px-1.5 py-0.5 rounded">Soon</span>
            </span>

            <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-emerald-400 opacity-50 cursor-not-allowed">
                <span class="text-base">📕</span>
                <span>Quran Reader</span>
                <span class="ml-auto text-xs bg-emerald-900 text-emerald-500 px-1.5 py-0.5 rounded">Soon</span>
            </span>

        </nav>

        {{-- Tombol Logout --}}
        <div class="px-3 py-4 border-t border-emerald-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                           text-emerald-200 hover:bg-red-500/20 hover:text-red-300
                           transition-colors text-left">
                    <span class="text-base">🚪</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- AREA KONTEN --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-3.5 flex items-center justify-between flex-shrink-0">
            <h1 class="text-base font-semibold text-gray-700">
                @yield('page-title', 'Dashboard')
            </h1>
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-400">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
                <div class="flex items-center gap-2">
                    <span class="text-base">🎓</span>
                    <span class="text-xs font-medium text-gray-600">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        {{-- Notifikasi session --}}
        @if (session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-start gap-2">
                <span class="mt-0.5">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm flex items-start gap-2">
                <span class="mt-0.5">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Konten halaman --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>

</div>

@stack('scripts')
</body>
</html>
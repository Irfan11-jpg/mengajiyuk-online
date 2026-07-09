<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru') — MengajiYuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">

<div class="flex min-h-screen">

    {{-- SIDEBAR GURU --}}
    <aside class="w-64 bg-amber-900 text-white flex flex-col flex-shrink-0">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-amber-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-xl">🕋</span>
                </div>
                <div>
                    <p class="font-bold text-base leading-tight">MengajiYuk</p>
                    <p class="text-amber-300 text-xs">Portal Guru</p>
                </div>
            </div>
        </div>

        {{-- Info guru yang login --}}
        <div class="px-6 py-4 border-b border-amber-800 bg-amber-950/30">
            <p class="text-xs text-amber-400 mb-0.5">Selamat datang,</p>
            <p class="font-semibold text-sm truncate">{{ Auth::user()->name }}</p>
            <span class="inline-block mt-1.5 text-xs bg-amber-700/50 text-amber-200 px-2.5 py-0.5 rounded-full border border-amber-700">
                Pengajar / Ustaz
            </span>
        </div>

        {{-- Menu navigasi --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <p class="text-amber-400 text-xs font-medium uppercase tracking-wider px-3 mb-2 mt-1">
                Menu Guru
            </p>

            <a href="{{ route('guru.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('guru.dashboard')
                          ? 'bg-amber-700 text-white font-medium'
                          : 'text-amber-100 hover:bg-amber-800/50' }}">
                <span class="text-base">🏠</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('guru.validasi.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('guru.validasi.*')
                         ? 'bg-amber-700 text-white font-medium'
                         : 'text-amber-100 hover:bg-amber-800/50' }}">
                <span class="text-base">✅</span>
                <span>Validasi Setoran</span>
            </a>
        </nav>

        {{-- Tombol Logout --}}
        <div class="px-3 py-4 border-t border-amber-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                           text-amber-200 hover:bg-red-500/20 hover:text-red-300
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
                @yield('page-title', 'Dashboard Guru')
            </h1>
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-400">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
                <div class="flex items-center gap-2">
                    <span class="text-base">👨‍🏫</span>
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
            <div class="mx-6 mt-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm flex items-start gap-2">
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
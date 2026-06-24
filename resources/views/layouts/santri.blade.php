@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">

    <!-- Sidebar Santri -->
    <aside class="w-64 bg-emerald-700 text-white flex flex-col fixed inset-y-0 left-0">
        <div class="px-6 py-6 border-b border-emerald-600">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-xl">📖</div>
                <div>
                    <p class="font-bold leading-tight">MengajiYuk</p>
                    <p class="text-xs text-emerald-200">Area Santri</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('santri.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('santri.dashboard') ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10' }}">
                <span>🏠</span> Dashboard
            </a>
            <a href="{{ route('santri.hafalan.progres') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('santri.hafalan.progres') ? 'bg-white/15 text-white' : 'text-emerald-100 hover:bg-white/10' }}">
                <span>📿</span> Progres 30 Juz
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-emerald-600">
            <div class="px-4 py-2 mb-2">
                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-emerald-200">Kelas {{ auth()->user()->kelas ?? '-' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-emerald-100 hover:bg-white/10 transition">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
        @yield('santri-content')
    </main>

</div>
@endsection
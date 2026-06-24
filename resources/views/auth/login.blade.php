<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MengajiYuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-100 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- LOGO DAN JUDUL --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-2xl mb-4 shadow-lg">
            <span class="text-white text-3xl">🕋</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">MengajiYuk</h1>
        <p class="text-gray-500 text-sm mt-1">Tracker Hafalan & Jurnal Ibadah Santri</p>
    </div>

    {{-- CARD LOGIN --}}
    <div class="bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-lg font-semibold text-gray-700 mb-6 text-center">
            Masuk ke Akun Anda
        </h2>

        {{-- Pesan sukses setelah logout --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg px-4 py-3 mb-5 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error dari session --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Field Email --}}
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Alamat Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email Anda"
                    required
                    autofocus
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800
                           placeholder-gray-400 focus:outline-none focus:ring-2
                           focus:ring-emerald-500 focus:border-transparent transition
                           {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Field Password --}}
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password Anda"
                    required
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800
                           placeholder-gray-400 focus:outline-none focus:ring-2
                           focus:ring-emerald-500 focus:border-transparent transition
                           {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox Remember Me --}}
            <div class="flex items-center mb-6">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer"
                >
                <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer">
                    Ingat saya di perangkat ini
                </label>
            </div>

            {{-- Tombol Login --}}
            <button
                type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800
                       text-white font-semibold py-2.5 px-4 rounded-lg transition
                       duration-150 focus:outline-none focus:ring-2 focus:ring-emerald-500
                       focus:ring-offset-2"
            >
                Masuk
            </button>

            {{-- TIDAK ADA link atau tombol Register di sini --}}

        </form>

        {{-- INFO AKUN DEMO --}}
        <div class="mt-6 pt-6 border-t border-gray-100">
            <p class="text-xs text-gray-400 text-center mb-3 font-medium">
                Akun Demo untuk Presentasi UAS
            </p>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-3">
                    <p class="text-xs font-semibold text-amber-700 mb-1.5">Guru</p>
                    <p class="text-xs text-gray-500 font-mono leading-relaxed">
                        guru@mengajiyuk.com<br>
                        <span class="text-gray-400">password: password</span>
                    </p>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3">
                    <p class="text-xs font-semibold text-emerald-700 mb-1.5">Santri</p>
                    <p class="text-xs text-gray-500 font-mono leading-relaxed">
                        santri@mengajiyuk.com<br>
                        <span class="text-gray-400">password: password</span>
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        &copy; {{ date('Y') }} MengajiYuk &mdash; Tugas UAS Laravel 12
    </p>

</div>

</body>
</html>
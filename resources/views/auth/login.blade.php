<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MengajiYuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-emerald-50 via-white to-amber-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-600 text-white text-2xl font-bold mb-4 shadow-lg shadow-emerald-200">
                📖
            </div>
            <h1 class="text-2xl font-bold text-gray-800">MengajiYuk</h1>
            <p class="text-gray-500 text-sm mt-1">Aplikasi Tracking Hafalan Al-Qur'an</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Masuk ke Akun Anda</h2>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="input-field"
                        placeholder="nama@mengajiyuk.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        class="input-field"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <button type="submit" class="btn-primary w-full">
                    Masuk
                </button>
            </form>
        </div>

        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-600">
            <p class="font-semibold text-gray-700 mb-2">Akun Demo</p>
            <div class="space-y-1">
                <p>👨‍🏫 Guru &nbsp;: <span class="font-mono">guru@mengajiyuk.com</span> / <span class="font-mono">password</span></p>
                <p>🧑‍🎓 Santri: <span class="font-mono">santri@mengajiyuk.com</span> / <span class="font-mono">password</span></p>
            </div>
        </div>
    </div>

</body>
</html>
<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /**
         * Daftarkan RoleMiddleware dengan alias 'role'.
         *
         * Setelah didaftarkan di sini, middleware bisa dipakai
         * di routes/web.php dengan cara:
         *
         *   Route::middleware(['auth', 'role:guru'])->group(...)
         *   Route::middleware(['auth', 'role:santri'])->group(...)
         *
         * Parameter setelah titik dua (:) dikirim sebagai
         * argumen $role ke method handle() di RoleMiddleware.
         *
         * PENTING - LARAVEL 12:
         * Di Laravel 12 middleware didaftarkan di bootstrap/app.php
         * bukan di app/Http/Kernel.php seperti Laravel versi lama.
         * Jika melihat tutorial lama yang memakai Kernel.php, abaikan
         * dan gunakan cara ini sebagai gantinya.
         */
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
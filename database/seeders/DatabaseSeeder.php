<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Daftar seeder yang dijalankan secara berurutan
     * saat menjalankan perintah: php artisan db:seed
     *
     * Untuk Mhs 1 cukup UserSeeder saja.
     * Mhs 2 dan Mhs 3 bisa menambah seeder lain di sini nanti.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $this->call([
            BadgeSeeder::class,
        ]);
    }
}
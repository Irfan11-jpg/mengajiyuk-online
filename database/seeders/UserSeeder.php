<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menanam 2 akun dummy permanen untuk keperluan demo UAS.
     *
     * Akun yang dibuat:
     * 1. Guru   → guru@mengajiyuk.com   | password | role: guru
     * 2. Santri → santri@mengajiyuk.com | password | role: santri | kelas: 10A
     *
     * Menggunakan updateOrCreate agar jika seeder dijalankan
     * berkali-kali tidak membuat akun duplikat.
     * Cari berdasarkan email, jika sudah ada update datanya,
     * jika belum ada buat baru.
     */
    public function run(): void
    {
        // =====================================================
        // AKUN GURU
        // =====================================================
        User::updateOrCreate(
            // Kondisi pencarian berdasarkan email
            ['email' => 'guru@mengajiyuk.com'],

            // Data yang diisi saat create atau update
            [
                'name'     => 'Ustaz Ahmad Fauzi',
                'email'    => 'guru@mengajiyuk.com',
                'password' => Hash::make('password'),
                'role'     => 'guru',
                'kelas'    => null,
            ]
        );

        // =====================================================
        // AKUN SANTRI
        // =====================================================
        User::updateOrCreate(
            // Kondisi pencarian berdasarkan email
            ['email' => 'santri@mengajiyuk.com'],

            // Data yang diisi saat create atau update
            [
                'name'     => 'Muhammad Rizki Ramadhan',
                'email'    => 'santri@mengajiyuk.com',
                'password' => Hash::make('password'),
                'role'     => 'santri',
                'kelas'    => '10A',
            ]
        );

        // Tampilkan informasi di terminal setelah seeder selesai
        $this->command->info('');
        $this->command->info('Akun dummy berhasil dibuat:');
        $this->command->line('');
        $this->command->line('   GURU');
        $this->command->line('   Email    : guru@mengajiyuk.com');
        $this->command->line('   Password : password');
        $this->command->line('   Role     : guru');
        $this->command->line('');
        $this->command->line('   SANTRI');
        $this->command->line('   Email    : santri@mengajiyuk.com');
        $this->command->line('   Password : password');
        $this->command->line('   Role     : santri');
        $this->command->line('   Kelas    : 10A');
        $this->command->line('');
    }
}
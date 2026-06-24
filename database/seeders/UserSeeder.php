<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'guru@mengajiyuk.com'],
            [
                'name' => 'Ustadz Ahmad',
                'email' => 'guru@mengajiyuk.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'kelas' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'santri@mengajiyuk.com'],
            [
                'name' => 'Muhammad Irfan',
                'email' => 'santri@mengajiyuk.com',
                'password' => Hash::make('password'),
                'role' => 'santri',
                'kelas' => '10A',
            ]
        );
    }
}
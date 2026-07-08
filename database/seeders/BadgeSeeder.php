<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [

            [
                'nama' => 'Santri Pemula',
                'icon' => '🌱',
                'deskripsi' => 'Mengisi jurnal pertama kali',
                'target' => 1,
                'tipe' => 'streak',
            ],

            [
                'nama' => 'Istiqamah',
                'icon' => '🔥',
                'deskripsi' => 'Streak 7 hari',
                'target' => 7,
                'tipe' => 'streak',
            ],

            [
                'nama' => 'Pejuang Ibadah',
                'icon' => '⭐',
                'deskripsi' => 'Streak 30 hari',
                'target' => 30,
                'tipe' => 'streak',
            ],

            [
                'nama' => 'Tilawah Lover',
                'icon' => '📖',
                'deskripsi' => 'Tilawah 30 kali',
                'target' => 30,
                'tipe' => 'tilawah',
            ],

            [
                'nama' => 'Tahajud Hero',
                'icon' => '🌙',
                'deskripsi' => 'Tahajud 20 kali',
                'target' => 20,
                'tipe' => 'tahajud',
            ],

            [
                'nama' => 'Murajaah Master',
                'icon' => '🕌',
                'deskripsi' => 'Murajaah 50 kali',
                'target' => 50,
                'tipe' => 'murajaah',
            ],

        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['nama' => $badge['nama']],
                $badge
            );
        }
    }
}
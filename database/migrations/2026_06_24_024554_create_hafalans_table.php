<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel hafalans menyimpan setiap setoran hafalan santri.
     * Satu baris = satu kali setoran surah tertentu.
     *
     * Nama tabel 'hafalans' (plural dengan huruf s) agar Laravel
     * otomatis mengenali dari Model Hafalan.php tanpa perlu
     * menulis property $table secara manual di dalam model.
     */
    public function up(): void
    {
        Schema::create('hafalans', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users (santri yang menyetor hafalan)
            // onDelete cascade: jika santri dihapus, hafalannya ikut terhapus
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Nomor surah dalam Al-Quran (1 = Al-Fatihah, 114 = An-Nas)
            $table->unsignedTinyInteger('nomor_surah');

            // Nama surah disimpan langsung agar mudah ditampilkan
            // tanpa perlu query tambahan ke API atau tabel lain
            $table->string('nama_surah');

            // Rentang ayat yang disetor pada sesi ini
            // Contoh: ayat_awal=1, ayat_akhir=7 artinya ayat 1 sampai 7
            $table->unsignedSmallInteger('ayat_awal');
            $table->unsignedSmallInteger('ayat_akhir');

            // Jenis setoran:
            // 'ziyadah'  = hafalan baru yang belum pernah disetor sebelumnya
            // 'murojaah' = pengulangan hafalan yang sudah pernah disetor
            $table->enum('jenis', ['ziyadah', 'murojaah'])->default('ziyadah');

            // Tingkat kelancaran saat menyetor:
            // 'mutqin'  = sangat lancar, fasih, tidak ada kesalahan
            // 'lancar'  = lancar tapi ada sedikit kesalahan kecil
            // 'terbata' = masih terbata-bata, perlu banyak latihan lagi
            $table->enum('kelancaran', ['mutqin', 'lancar', 'terbata'])->default('lancar');

            // Nilai yang diberikan guru setelah mendengarkan setoran
            // nullable karena awalnya belum dinilai (status masih pending)
            // A = Sangat Baik, B = Baik, C = Cukup, D = Perlu Latihan
            $table->enum('nilai', ['A', 'B', 'C', 'D'])->nullable();

            // Status setoran:
            // 'pending'  = santri sudah input, menunggu guru menilai
            // 'approved' = guru sudah mendengarkan dan memberikan nilai
            $table->enum('status', ['pending', 'approved'])->default('pending');

            // Catatan evaluasi dari guru (opsional, tidak wajib diisi)
            // Contoh: "Perbaiki panjang pendek di ayat 5"
            $table->text('catatan_guru')->nullable();

            // Relasi ke guru yang menilai setoran ini
            // nullable karena awalnya pending (belum ada guru yang menilai)
            // onDelete set null: jika guru dihapus, kolom ini jadi null
            // tapi data hafalannya tetap tersimpan
            $table->foreignId('dinilai_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Dijalankan saat: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalans');
    }
};
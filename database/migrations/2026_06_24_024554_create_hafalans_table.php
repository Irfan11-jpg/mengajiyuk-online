<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hafalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('nomor_surah');
            $table->string('nama_surah');
            $table->unsignedSmallInteger('ayat_awal');
            $table->unsignedSmallInteger('ayat_akhir');
            $table->enum('jenis', ['ziyadah', 'murojaah'])->default('ziyadah');
            $table->enum('kelancaran', ['lancar', 'kurang_lancar', 'tidak_lancar'])->nullable();
            $table->unsignedTinyInteger('nilai')->nullable();
            $table->enum('status', ['pending', 'approved', 'revisi'])->default('pending');
            $table->text('catatan_guru')->nullable();
            $table->foreignId('dinilai_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalans');
    }
};
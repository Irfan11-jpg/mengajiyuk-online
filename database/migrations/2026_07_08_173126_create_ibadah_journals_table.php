<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ibadah_journals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->date('tanggal');

            $table->boolean('subuh')->default(false);
            $table->boolean('dzuhur')->default(false);
            $table->boolean('ashar')->default(false);
            $table->boolean('maghrib')->default(false);
            $table->boolean('isya')->default(false);

            $table->boolean('tilawah')->default(false);
            $table->boolean('murajaah')->default(false);
            $table->boolean('tahajud')->default(false);

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique(['user_id','tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibadah_journals');
    }
};
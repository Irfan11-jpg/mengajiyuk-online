<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedInteger('current_streak')->default(0);

            $table->unsignedInteger('best_streak')->default(0);

            $table->date('last_journal_date')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'current_streak',
                'best_streak',
                'last_journal_date'
            ]);

        });
    }
};
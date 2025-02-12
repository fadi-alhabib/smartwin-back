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
        Schema::table('c4_games', function (Blueprint $table) {
            $table->time('end_time')->nullable();
            $table->boolean('game_over')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('c4_games', function (Blueprint $table) {
            //
        });
    }
};

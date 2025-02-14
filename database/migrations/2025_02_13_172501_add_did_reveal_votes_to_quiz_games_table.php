<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('quiz_games', function (Blueprint $table) {
            $table->boolean('did_reveal_votes')->default(false)->after('game_over');
        });
    }

    public function down()
    {
        Schema::table('quiz_games', function (Blueprint $table) {
            $table->dropColumn('did_reveal_votes');
        });
    }
};

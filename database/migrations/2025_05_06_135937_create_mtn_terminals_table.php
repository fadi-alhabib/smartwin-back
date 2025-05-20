<?php

// database/migrations/2024_04_29_000001_create_mtn_terminals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mtn_terminals', function (Blueprint $table) {
            $table->id();
            $table->string('terminal_id')->unique();
            $table->json('settings')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mtn_terminals');
    }
};

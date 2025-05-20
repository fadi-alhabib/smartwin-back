<?php
// database/migrations/2024_04_29_000000_create_mtn_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mtn_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('session_number')->nullable();
            $table->integer('amount');
            $table->tinyInteger('status')->default(1);
            $table->string('phone')->nullable();
            $table->string('guid')->nullable();
            $table->string('transaction_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mtn_payments');
    }
};

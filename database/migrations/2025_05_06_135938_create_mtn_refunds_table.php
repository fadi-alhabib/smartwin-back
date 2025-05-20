<?php
// database/migrations/2024_04_29_000002_create_mtn_refunds_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mtn_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mtn_payment_id')->constrained('mtn_payments');
            $table->string('base_invoice');
            $table->string('refund_invoice')->nullable();
            $table->integer('refund_amount')->nullable();
            $table->integer('commission')->nullable();
            $table->integer('tax_sender')->nullable();
            $table->tinyInteger('status')->default(0); // 0=initiated,1=confirmed,2=canceled
            $table->json('parameters')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mtn_refunds');
    }
};

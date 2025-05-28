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
        Schema::create('syriatel_payments', function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->string("customerMSISDN")->nullable();
            $table->string("amount")->nullable();
            $table->string("transactionID")->nullable();
            $table->string('OTP')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean("complete")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syriatel_payments');
    }
};

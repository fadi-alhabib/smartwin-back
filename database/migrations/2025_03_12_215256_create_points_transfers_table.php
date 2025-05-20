<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('points_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->bigInteger('points');
            $table->enum('type', ['store', 'admin'])->default('admin');
            $table->boolean('accepted')->default(false);
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');


            $table->index('user_id');
            $table->index('store_id');
            $table->index('admin_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('points_transfers');
    }
};

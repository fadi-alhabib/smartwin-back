<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Remove the existing foreign key constraint on user_id
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Rename user_id to admin_id
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'admin_id');
        });

        // Re-add the foreign key constraint referencing the admins table
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Remove the foreign key constraint on admin_id
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
        });

        // Rename admin_id back to user_id
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'user_id');
        });

        // Re-add the original foreign key constraint referencing the users table
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

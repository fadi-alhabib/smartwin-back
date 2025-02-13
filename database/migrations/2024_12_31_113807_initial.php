<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique(); // Ensure privilege names are unique
            $table->timestamps();
        });

        Schema::create('admins_privileges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('privilege_id');
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('privilege_id')->references('id')->on('privileges')->onDelete('cascade');

            // Composite unique constraint to prevent The admin of having the same privilage more than once
            $table->unique(['admin_id', 'privilege_id']);
        });

        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('country');
            $table->string('phone');
            $table->bigInteger('points')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('user_id');
            $table->index('country');
        });

        Schema::create('transfer_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('points')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('user_id');
        });

        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type');
            $table->string('country');
            $table->string('address');
            $table->string('phone');
            $table->string('image')->nullable();
            $table->bigInteger('points')->default(0);
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('user_id');
            $table->index('is_active');
            $table->index('country');

            // Ensure store names are unique within a country
            // $table->unique(['name', 'country']);
        });


        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0.00); // Default value for price
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('store_id');
            $table->index('price');
        });

        Schema::create('product_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('rating')->unsigned()->comment('Rating value between 1 and 5');
            $table->text('review')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('product_id');
            $table->index('user_id');

            // Ensure a user can only rate a product once
            $table->unique(['product_id', 'user_id']);

            // Add check constraint for rating (1 to 5)
            // $table->check('rating >= 1 AND rating <= 5');
        });
        DB::statement('ALTER TABLE product_ratings ADD CONSTRAINT chk_rating_range CHECK (rating >= 1 AND rating <= 5)');

        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('product_id');
        });

        Schema::create('advertisements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('path');
            $table->boolean('home_ad')->default(false); // Default value for home_ad
            $table->integer('priority')->default(0); // Default value for priority
            $table->boolean('is_img')->default(false); // Default value for is_img
            $table->date('from_date');
            $table->date('to_date');
            $table->boolean('is_active')->default(true); // Default value for is_active
            $table->timestamps();

            // Index on frequently queried columns
            $table->index('priority');
            $table->index('is_active');
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image');
            $table->boolean('online')->default(true); // Default value for online
            $table->unsignedBigInteger('host_id');
            $table->integer('available_time')->default(20);
            $table->timestamps();

            $table->foreign('host_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('host_id');
            $table->index('online');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->string('message');

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('c4_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('challenger_id');
            $table->json('board')->nullable(); // 6 rows x 7 columns
            $table->integer('time_consumed')->default(0);
            $table->unsignedBigInteger('current_turn'); // host_id or challenger_id
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('current_turn')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('quiz_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->integer('questions_count')->default(1);
            $table->integer('right_answers_count')->default(0);
            $table->integer('time_consumed')->default(0);
            $table->boolean('images_game')->default(false);
            $table->boolean('game_over')->default(false);
            $table->time('end_time')->nullable();
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });

        Schema::create('time_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            // SUGGESTION: ADD SPECTATORS CAN BUY TIME TOO
            $table->integer('additional_minutes');
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->boolean('status')->default(false); // Default value for status
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('user_id');
            $table->index('status');
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('question_id');
            $table->string('title');
            $table->boolean('is_correct')->default(false); // Default value for is_correct
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('question_id');
        });

        Schema::create('trades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'accepted', 'denied'])->default('pending'); // Default value for status
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index on frequently queried columns
            $table->index('product_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trades');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('advertisements');
        Schema::dropIfExists('images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('transfer_store_points');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('transfer_requests');
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('product_ratings');
        Schema::dropIfExists('admins_privileges');
        Schema::dropIfExists('privileges');
    }
};

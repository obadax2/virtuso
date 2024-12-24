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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('users_id'); // User ID
            $table->unsignedBigInteger('post_id'); // Define post_id only once
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade'); // Foreign key for users
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade'); // Foreign key for posts
            $table->string('comment'); // Comment content
            $table->integer('likes'); // Likes count
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

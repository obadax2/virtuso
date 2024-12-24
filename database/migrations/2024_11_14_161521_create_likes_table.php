<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id(); // Creates an 'id' column
            $table->unsignedBigInteger('post_id'); // Must match 'posts.id'
            $table->unsignedBigInteger('user_id');  // Correctly define the user_id column
            // Add the foreign key constraint
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // Foreign key constraint
            $table->foreign('post_id')
                  ->references('id')->on('posts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
}
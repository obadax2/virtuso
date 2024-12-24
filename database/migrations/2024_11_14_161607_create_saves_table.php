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
        Schema::create('saves', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saves');
    }
};

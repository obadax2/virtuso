<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('saves', function (Blueprint $table) {
    $table->unsignedBigInteger('post_id');  // Reference to posts table
    $table->unsignedBigInteger('user_id');  // Reference to users table

    $table->foreign('user_id')
          ->references('id')->on('users')
          ->onDelete('cascade')
          ->onUpdate('cascade');
    $table->foreign('post_id')
          ->references('id')->on('posts')
          ->onDelete('cascade')
          ->onUpdate('cascade');

    $table->timestamps();  // Adds created_at and updated_at columns
});

    }

    public function down()
    {
        Schema::table('saves', function (Blueprint $table) {
            $table->dropTimestamps(); // Remove created_at and updated_at columns
        });
    }

};

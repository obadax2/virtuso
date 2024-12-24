<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreateMusicTable extends Migration
{
    public function up()
    {
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            // Define the user_id column
            $table->unsignedBigInteger('user_id');  // Correctly define the user_id column
            // Add the foreign key constraint
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('music_type');
            $table->enum('type', ['genre', 'url'])->default('genre');
            $table->string('music');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('music');
    }
}
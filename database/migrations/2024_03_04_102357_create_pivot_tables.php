<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_movie', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });

        Schema::create('movie_starship', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('starship_id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->foreign('starship_id')->references('id')->on('starships')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });

        Schema::create('movie_planet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planet_id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->foreign('planet_id')->references('id')->on('planets')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_movie');
        Schema::dropIfExists('movie_starship');
        Schema::dropIfExists('movie_planet');
    }
};

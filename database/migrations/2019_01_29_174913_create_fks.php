<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('hotel_id');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('hotel_id')->references('id')->on('hotels');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedInteger('platform_id');
            $table->unsignedInteger('hotel_id');
            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->foreign('hotel_id')->references('id')->on('hotels');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->unsignedInteger('review_id');
            $table->foreign('review_id')->references('id')->on('reviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->string('place_id')->primary();
            $table->string('search')->index()->nullable();
            $table->string('name')->nullable();
            $table->string('geometry_lat')->nullable();
            $table->string('geometry_lng')->nullable();
            $table->string('rating')->nullable();
            $table->integer('user_ratings_total')->nullable();
            $table->json('types')->nullable();
            $table->string('vicinity')->nullable();
            $table->string('icon_url')->nullable();
            $table->text('photo')->nullable();
            $table->json('full_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}

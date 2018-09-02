<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaravelPlacePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laravel-place', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('imageorig');
        });

        Schema::create('laravel-place-photo', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('place_id')->unsigned();
            $table->foreign('place_id')->references('id')->on('laravel-place');
            $table->string('image')->nullable();
            $table->string('imageorig')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel-place-photo');
    }
}

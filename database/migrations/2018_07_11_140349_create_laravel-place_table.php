<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaravelPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // テーブルを生成する
        Schema::create(
            'laravel-place',
            function(Blueprint $table){
                $table->increments('id');
                $table->integer('user_id');
                $table->string('desc');
                $table->string('owner');
                $table->float('lat');
                $table->float('lng');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // テーブルを削除する
        Schema::dropIfExists('laravel-place');
    }
}

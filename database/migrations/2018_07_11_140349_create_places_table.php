<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Log; // ログ出力で使用

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Log::info('2018_07_11_140349_create_place_table.php CreatePlacesTable::up()');

        // テーブルを生成する
        Schema::create(
            'places',
            function(Blueprint $table){
                $table->increments('id');
                $table->integer('user_id');
                $table->string('desc')->nullable();
                $table->string('owner')->nullable();
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
        Log::info('2018_07_11_140349_create_place_table.php CreatePlacesTable::down()');

        // テーブルを削除する
        Schema::dropIfExists('places');
    }
}

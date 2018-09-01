<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnImageLaravelPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laravel-place', function (Blueprint $table) {
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
        Schema::table('laravel-place', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('imageorig');
        });
    }
}

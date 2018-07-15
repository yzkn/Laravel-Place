<?php

use Illuminate\Database\Seeder;
use App\LaravelPlace;

class LaravelPlaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'desc' => 'foo',
            'user_id' => 1,
            'lat' => 35.0,
            'lng' => 140.0
        ];
        $laravel_place = new LaravelPlace;
        $laravel_place->fill($param)->save();
        $param = [
            'desc' => 'bar',
            'user_id' => 2,
            'lat' => 35.1,
            'lng' => 140.1
        ];
        $laravel_place = new LaravelPlace;
        $laravel_place->fill($param)->save();
    }
}

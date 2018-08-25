<?php

use App\Providers\AuthServiceProvider;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::resource('place', 'PlaceController');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('search', 'PlaceController@search')->name('place.search');
Route::get('where', 'PlaceController@where');
Route::post('where', 'PlaceController@where');

Route::group(['middleware' => ['auth', 'can:'.AuthServiceProvider::ROLE_GT_SYSADMIN]], function () { // システム管理者以上
    Route::resource('user', 'UserController', ['only' => ['index', 'update', 'destroy']]);
    Route::post('user', 'UserController@store');
});

Route::group(['middleware' => ['auth', 'can:'.AuthServiceProvider::ROLE_DEVELOPER]], function () { // 開発者のみ
    Route::get('csv/import', 'CsvController@import');
    Route::post('csv/import', 'CsvController@store');
    Route::get('csv/export', 'CsvController@export');
    Route::post('csv/export', 'CsvController@write');
});

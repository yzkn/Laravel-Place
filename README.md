# LaravelPlace

## CRUD

[Composer](https://getcomposer.org/Composer-Setup.exe)のインストーラをダウンロード

以下のコマンドを実行

> $ .\Composer-Setup.exe
>
> $ composer global require "laravel/installer"
>
> $ cd .\Documents\works\PHP
>
> $ laravel new laravel-place

[PostgreSQL](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads)のインストーラをダウンロード

以下のコマンドを実行

> $ .\postgresql-10.4-1-windows-x64.exe

以下をPATHに追加

```text
C:\Program Files\PostgreSQL\10\bin
```

以下のコマンドを実行

> $ postgres -V
>
> $ createdb -O postgres -U postgres laravel-place

接続情報を設定

> $ nano .\Documents\works\PHP\laravel-place\.env

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secrets
```

のブロックを、以下のように修正(DB_USERNAMEとDB_PASSWORDはDB作成時に指定したもの)

```text
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel-place
DB_USERNAME=postgres
DB_PASSWORD=password
```

php.ini の以下の行のコメントを解除

```text
;extension=pdo_pgsql
```

以下のコマンドを実行

> $ cd .\Documents\works\PHP\laravel-place
> $ php artisan make:migration create_laravel-place_table

生成結果が以下のように表示されるので控えておく

```text
Created Migration: 2018_07_11_140349_create_laravel-place_table
```

以下のコマンドを実行 (Y_m_d_hisは実行日時)

> $ nano .\Documents\works\PHP\laravel-place\database\migrations\Y_m_d_his_create_laravel-place_table.php

```php
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

```

以下のコマンドを実行

> php artisan migrate

```text
Migrated
```

以下のコマンドを実行

> php artisan make:model LaravelPlace

```text
Model created successfully.
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\LaravelPlace.php

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaravelPlace extends Model
{
    protected $table = 'laravel-place';
    protected $guarded = array('id');

    public static $rules = array(
        'id' => 'required',
        'lat' => 'numeric',
        'lng' => 'numeric'
    );

    public function getData(){
        return $this->id . '\t' . $this;
    }
}

```

以下のコマンドを実行

> $ php artisan make:seeder LaravelPlaceTableSeeder

```text
Seeder created successfully.
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\database\seeds\LaravelPlaceTableSeeder.php

```php
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
            'owner' => '1',
            'lat' => 35.0,
            'lng' => 140.0
        ];
        $laravel_place = new LaravelPlace;
        $laravel_place->fill($param)->save();
        $param = [
            'desc' => 'bar',
            'owner' => '2',
            'lat' => 35.1,
            'lng' => 140.1
        ];
        $laravel_place = new LaravelPlace;
        $laravel_place->fill($param)->save();
    }
}

```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\database\seeds\DatabaseSeeder.php

```php
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaravelPlaceTableSeeder::class);
    }
}

```

以下のコマンドを実行

> $ php artisan db:seed

```text
Seeding: LaravelPlaceTableSeeder
```

以下のコマンドを実行

> php artisan make:controller PlaceController --resource

```text
Controller created successfully.
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\routes\web.php

```php
<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('place', 'PlaceController');

// Route::get('placemanage/place', 'PlaceController@place');

```

以下のコマンドを実行

> $ php artisan serve

以下のURLにアクセスして、エラーが表示されていないことを確認

> http://127.0.0.1:8000

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\PlaceController.php

```text
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelPlace;

use Illuminate\Support\Facades\DB; // ページネーションで使用

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // シンプルな例
        // $items = LaravelPlace::all();
        // return $items->toArray();

        // ページネーションありの例
        $ipp = 5;
        $items = LaravelPlace::orderBy('id', 'asc')->simplePaginate($ipp);

        if(isset($items)){
            return view('placemanage.index', ['items' => $items]);
        }
        return redirect('/place');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('placemanage.place-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $laravel_place = new LaravelPlace();
        $form = $request->all();
        unset($form['_token']);
        $laravel_place->fill($form)->save();
        return redirect('/place');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $laravel_place = LaravelPlace::find($id);
        if(isset($laravel_place)){
            return view('placemanage.place-show', ['form' => $laravel_place]);
        }
        return redirect('/place');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $laravel_place = LaravelPlace::find($id);
        if(isset($laravel_place)){
            return view('placemanage.place-edit', ['form' => $laravel_place]);
        }
        return redirect('/place');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, LaravelPlace::$rules);
        $laravel_place = LaravelPlace::find($request->id);
        if(isset($laravel_place)){
            $form = $request->all();
            unset($form['_token']);
            $laravel_place->fill($form)->save();
        }
        return redirect('/place');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $laravel_place = LaravelPlace::find($id);
        if(isset($laravel_place)){
            $laravel_place->delete();
        }
        return redirect('/place');
    }
}

```

以下のコマンドを実行

> $ mkdir .\Documents\works\PHP\laravel-place\resources\views\placemanage

作成したplacemanageフォルダの直下に、以下のファイルを作成

- メインページとして使用する、アイテム一覧ページのテンプレート
  - index.blade.php

- CRUD処理を行うためのページのテンプレート
  - place-create.blade.php
  - place-edit.blade.php
  - place-show.blade.php

- 上記ページなどから呼び出して使用するフォーム部分のテンプレート
  - form-create.blade.php
  - form-edit.blade.php
  - form-show.blade.php

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-create.blade.php
> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-edit.blade.php
> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-show.blade.php
> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\index.blade.php
> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\place-create.blade.php
> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\place-edit.blade.php
> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\place-show.blade.php

## 認証の追加

以下のコマンドを実行

> php artisan make:auth

```text
Authentication scaffolding generated successfully.
```

> php artisan migrate

プロジェクト作成時点で既に作成されている場合は以下のような結果となる

```text
Nothing to migrate.
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\PlaceController.php

既存のuse演算子の行の辺りに以下の行を追加

```php
use Illuminate\Support\Facades\Auth; // 認証で使用
```

indexメソッドを修正

```php
    public function index()
    {
        $auth_user = Auth::user();
        $ipp = 5;
        $items = LaravelPlace::orderBy('id', 'asc')->simplePaginate($ipp);

        if(isset($items)){
            return view('placemanage.index', ['items' => $items, 'user' => $auth_user]);
        }
        return redirect('/place');
    }
```

> $ nano .\Documents\GitHub\Laravel-Place\resources\views\placemanage\index.blade.php

Createリンクの行の辺りに以下の行を追加

```php
            <p>
                @if (Auth::check())
                Hi,  {{$user->name}}!
                @else
                <a href="/register">{{__('Register')}}</a> | <a href="/login">Sign in</a>
                @endif
            </p>
            <br />
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\routes\web.php

```php
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
```


以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\Auth\LoginController.php

既存のuse演算子の行の辺りに以下を追加

```php
use Auth;
use Illuminate\Http\Request;
```

他のメソッドと同じレベルに以下を追加

```php
public function logout(Request $request) {
    Auth::logout();
    return redirect('/login');
}
```

---

Copyright (c) 2018 YA-androidapp(https://github.com/YA-androidapp) All rights reserved.
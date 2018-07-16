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

    public function getData()
    {
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

```php
<table>
  <form action="/place" method="post">
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{old('desc')}}"></td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{old('owner')}}"></td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{old('lat')}}" step="0.000001"></td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{old('lng')}}" step="0.000001"></td></tr>
    <tr><th></th><td><input type="submit" value="send"></td></tr>
</form>
</table>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-edit.blade.php

```php
<table>
  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{$form->desc}}"></td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{$form->owner}}"></td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{$form->lat}}" step="0.000001"></td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{$form->lng}}" step="0.000001"></td></tr>
    <tr><th></th><td><input type="submit" value="Update"></td></tr>
  </form>

  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <tr><th></th><td><input type="submit" value="Delete"></td></tr>
  </form>
</table>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-show.blade.php

```php
<table>
  <form>
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{$form->desc}}" readonly="readonly" disabled="disabled"></td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{$form->owner}}" readonly="readonly" disabled="disabled"></td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{$form->lat}}" step="0.000001" readonly="readonly" disabled="disabled"></td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{$form->lng}}" step="0.000001" readonly="readonly" disabled="disabled"></td></tr>
  </form>
</table>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\index.blade.php

```php
@php
    $title = __('Places');
@endphp
<html>
<head>
    <meta charset="utf-8" />
    <style>
        .pagination { font-size: small; }
        .pagination li { display:inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title }}</h1>
        <div class="table-responsive">
            <p>
                @if (Auth::check())
                Hi,  {{$user->name}}!
                @else
                <a href="/register">{{__('Register')}}</a> | <a href="/login">Sign in</a>
                @endif
            </p>
            <br />
            <a href="{{ url('place/create') }}">Create</a>
            <hr />
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Owner') }}</th>
                        <th>{{ __('Creator') }}</th>
                        <th>{{ __('Latitude') }}</th>
                        <th>{{ __('Longitude') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Updated') }}</th>
                        <th>{{ __('Edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            <a href="{{ url('place/'.$item->id) }}">{{ $item->id }}</a>
                        </td>
                        <td>{{ $item->desc }}</td>
                        <td>{{ $item->owner }}</td>
                        <td>{{ $item->getUserName() }}</td>
                        <td>{{ $item->lat }}</td>
                        <td>{{ $item->lng }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{ url('place/'.$item->id.'/edit') }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $items->links() }}
        </div>
    </div>
</body>
</html>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\place-create.blade.php

```php
<html>
<head>
  <meta charset="utf-8" />
</head>
<body>
  @include('placemanage.form-create')
</body>
</html>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\place-edit.blade.php

```php
<html>
<head>
  <meta charset="utf-8" />
</head>
<body>
  @include('placemanage.form-edit')
</body>
</html>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\place-show.blade.php

```php
<html>
<head>
  <meta charset="utf-8" />
</head>
<body>
  @include('placemanage.form-show')
</body>
</html>

```

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

## バリデーションの追加

以下のコマンドを実行

> php artisan make:request PlaceRequest

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Requests\PlaceRequest.php

authorizeメソッドの先頭に以下を追加

```php
        if (strpos($this->path(), 'place/') === 0) {
            return true;
        }
```

rulesメソッドの中身を以下に変更

```php
        return [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:0,180'
        ];
```

他のメソッドと同じレベルに以下を追加

```php
    public function messages()
    {
        return [
            'lat.required'=>'必須項目です。',
            'lat.numeric'=>'数値を入力してください。',
            'lat.between'=>'-90から90の範囲で入力してください。',
            'lng.required'=>'必須項目です。',
            'lng.numeric'=>'数値を入力してください。',
            'lng.between'=>'0から180の範囲で入力してください。'
        ];
    }
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\PlaceController.php

既存のuse演算子の行の辺りに以下を追加

```php
use App\Http\Requests\PlaceRequest; // バリデーションで使用
```

引数の変更

```php
    public function store(Request $request)
    public function update(Request $request, $id)
```

それぞれ以下のように PlaceRequest へ変更

```php
    public function store(PlaceRequest $request)
    public function update(PlaceRequest $request, $id)
```


> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-create.blade.php

```php
<table>
  <form action="/place" method="post">
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{old('desc')}}">@if($errors->has('desc')){{implode($errors->get('desc'))}}@endif</td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{old('owner')}}">@if($errors->has('owner')){{implode($errors->get('owner'))}}@endif</td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{old('lat')}}" step="0.000001">@if($errors->has('lat')){{implode($errors->get('lat'))}}@endif</td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{old('lng')}}" step="0.000001">@if($errors->has('lng')){{implode($errors->get('lng'))}}@endif</td></tr>
    <tr><th></th><td><input type="submit" value="send"></td></tr>
</form>
</table>

```

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\form-edit.blade.php

```php
<table>
  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{$form->desc}}">@if($errors->has('desc')){{implode($errors->get('desc'))}}@endif</td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{$form->owner}}">@if($errors->has('owner')){{implode($errors->get('owner'))}}@endif</td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{$form->lat}}" step="0.000001">@if($errors->has('lat')){{implode($errors->get('lat'))}}@endif</td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{$form->lng}}" step="0.000001">@if($errors->has('lng')){{implode($errors->get('lng'))}}@endif</td></tr>
    <tr><th></th><td><input type="submit" value="Update"></td></tr>
  </form>

  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <tr><th></th><td><input type="submit" value="Delete"></td></tr>
  </form>
</table>

```

## テーブル間にリレーションシップの追加

| テーブル | テーブル種別 | キー | キー種別 |
|:---|:---:|:---:|:---:|
| Place | 子 | user_id | 外部キー |
| User | 主 | id | 主キー |

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\LaravelPlace.php

getDataメソッドの後に以下を追加

```php
  public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getUserName(){
        if($this->user !== NULL)
        {
            if($this->user->name !== NULL)
            {
                return $this->user->name;
            }
        }
        return '';
    }
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\User.php

$hiddenの後に以下を追加

```php
    public function places()
    {
        return $this->hasMany('App\LaravelPlace');
    }
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\HomeController.php

既存のuse演算子の行の辺りに以下の行を追加

```php
use Illuminate\Support\Facades\Auth; // 認証で使用
```

indexメソッドを修正

```php
    public function index()
    {
        $auth_user = Auth::user();
        return view('home',['user'=>$auth_user]);
    }
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\index.blade.php

table を以下に変更

```php
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Owner') }}</th>
                        <th>{{ __('Creator') }}</th>
                        <th>{{ __('Latitude') }}</th>
                        <th>{{ __('Longitude') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Updated') }}</th>
                        <th>{{ __('Edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            <a href="{{ url('place/'.$item->id) }}">{{ $item->id }}</a>
                        </td>
                        <td>{{ $item->desc }}</td>
                        <td>{{ $item->owner }}</td>
                        <td>{{ $item->getUserName() }}</td>
                        <td>{{ $item->lat }}</td>
                        <td>{{ $item->lng }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{ url('place/'.$item->id.'/edit') }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\resources\views\home.blade.php

@section('content') の一番外側のdiv終了タグの直前に以下を追加

```php

    <hr />

    Items which you made:
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Owner') }}</th>
                    <th>{{ __('Latitude') }}</th>
                    <th>{{ __('Longitude') }}</th>
                    <th>{{ __('Created') }}</th>
                    <th>{{ __('Updated') }}</th>
                    <th>{{ __('Edit') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($user->places as $item)
                <tr>
                    <td>
                        <a href="{{ url('place/'.$item->id) }}">{{ $item->id }}</a>
                    </td>
                    <td>{{ $item->desc }}</td>
                    <td>{{ $item->owner }}</td>
                    <td>{{ $item->lat }}</td>
                    <td>{{ $item->lng }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td>
                        <a href="{{ url('place/'.$item->id.'/edit') }}">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\PlaceController.php

storeメソッドを以下に修正

```php
    public function store(PlaceRequest $request)
    {
        $laravel_place = new LaravelPlace();
        $form = $request->all();
        unset($form['_token']);

        $auth_user = Auth::user();
        $laravel_place->user_id = $auth_user->id;
        $laravel_place->fill($form)->save();
        return redirect('/place');
    }
```

## 検索機能の追加

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\routes\web.php

```php
Route::get('search', 'PlaceController@search');
Route::get('where', 'PlaceController@where');
Route::post('where', 'PlaceController@where');
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\app\Http\Controllers\PlaceController.php

既存のuse演算子の行の辺りに以下を追加

```php
use Validator; // フォームからPOSTされるデータに対するバリデーションで使用
```

destroyメソッドの後ろに以下を追加

```php
    /**
     * Find  the specified resource from storage.
     *
     * @param Place\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $auth_user = Auth::user();
        $param = ['desc'=>'', 'items'=>NULL, 'user' => $auth_user];
        return view('placemanage.search', $param);
    }

    public function where(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [ 'desc' => 'required'] // (new PlaceRequest())->rules()
        );
        if($validator->fails())
        {
            return redirect('/search')
                ->withErrors($validator)
                ->withInput();
        }

        $ipp = 5;

        $place = new LaravelPlace();
        if($request->has('desc')){
            $place = $place->orWhere('desc','like','%'.$request->desc.'%');
        }
        $items = $place
            ->orderBy('id', 'asc')
            ->simplePaginate($ipp)
            ->appends($request->only(['desc']));
        $param = ['desc'=>$request->desc, 'items'=>$items];
        return view('placemanage.search', $param);
    }
```

以下のコマンドを実行

> $ nano .\Documents\works\PHP\laravel-place\resources\views\placemanage\search.blade.php

```php
@php
    $title = __('Places');
@endphp
<html>
<head>
    <meta charset="utf-8" />
    <style>
        .pagination { font-size: small; }
        .pagination li { display:inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title }}</h1>
        <div class="table-responsive">
            <div>
                <form action="/where" method="post">
                    {{ method_field('POST') }}
                    {{ csrf_field() }}
                    <input type="text" name="desc" value="">
                    <input type="submit" value="{{__('Search')}}">
                </form>
            </div>
            @if (isset($items))
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Owner') }}</th>
                        <th>{{ __('Creator') }}</th>
                        <th>{{ __('Latitude') }}</th>
                        <th>{{ __('Longitude') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Updated') }}</th>
                        <th>{{ __('Edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            <a href="{{ url('place/'.$item->id) }}">{{ $item->id }}</a>
                        </td>
                        <td>{{ $item->desc }}</td>
                        <td>{{ $item->owner }}</td>
                        <td>{{ $item->getUserName() }}</td>
                        <td>{{ $item->lat }}</td>
                        <td>{{ $item->lng }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{ url('place/'.$item->id.'/edit') }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if (NULL !== ($items->links()))
            {{ $items->appends(['desc' => $desc])->links() }}
            @endif
            @else
            該当するデータが見つかりませんでした。<br>
            @endif
        </div>
    </div>
</body>
</html>

```

---

Copyright (c) 2018 YA-androidapp(https://github.com/YA-androidapp) All rights reserved.
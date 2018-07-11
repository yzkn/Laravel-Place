# LaravelPlace

## Command

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


---

Copyright (c) 2018 YA-androidapp(https://github.com/YA-androidapp) All rights reserved.
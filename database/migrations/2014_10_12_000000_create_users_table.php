<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Log; // ログ出力で使用

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Log::info('2014_10_12_000000_create_users_table.php CreateUsersTable::up()');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('role')->default(0)->index('index_role');
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('lastlogin_at')->nullable();
        });

        DB::table('users')->insert(
            array(
                'name' => 'SysAdmin',
                'email' => 'admin@example.net',
                'password' => Hash::make(\Config::get('auth.default_value.password.sysadmin')),
                'role' => \Config::get('auth.default_value.role.sysadmin')
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Log::info('2014_10_12_000000_create_users_table.php CreateUsersTable::down()');

        Schema::dropIfExists('users');
    }
}

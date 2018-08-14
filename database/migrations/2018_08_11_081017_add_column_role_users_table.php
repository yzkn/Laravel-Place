<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Log; // ログ出力で使用

class AddColumnRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Log::info('2018_08_11_081017_add_column_role_users_table.php AddColumnRoleUsersTable::up()');

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('role')->default(0)->index('index_role');
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
        Log::info('2018_08_11_081017_add_column_role_users_table.php AddColumnRoleUsersTable::down()');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}

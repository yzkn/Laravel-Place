<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    const ROLE_DEVELOPER = 'devloper';  // システム開発者のみ
    const ROLE_GT_SYSADMIN = 'gt_sysadmin'; // システム管理者
    const ROLE_GT_EDITOR = 'gt_editor'; // 登録会員
    const ROLE_GT_GUEST = 'gt_guest'; // ゲスト会員

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // システム開発者のみ
        Gate::define(self::ROLE_DEVELOPER, function ($user) {
            return ($user->role == 1);
        });
        // システム開発者とシステム管理者
        Gate::define(self::ROLE_GT_SYSADMIN, function ($user) {
            return (10 >= $user->role && $user->role >= 1);
        });
        // システム開発者とシステム管理者と登録会員
        Gate::define(self::ROLE_GT_EDITOR, function ($user) {
            return (100 >= $user->role && $user->role >= 1);
        });
        // ゲスト会員も含めた全てのユーザー
        Gate::define(self::ROLE_GT_GUEST, function ($user) {
            // return (1000 >= $user->role && $user->role >= 1);
            return true;
        });
    }
}

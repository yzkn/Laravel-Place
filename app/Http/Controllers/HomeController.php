<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelPlace;

use Illuminate\Support\Facades\Auth; // 認証で使用

use Illuminate\Support\Facades\Log; // ログ出力で使用

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo \Config::get('auth.role_number.developer')==1; echo '#';
        // echo \Config::get('auth.role_number.gt_sysadmin')==10; echo '#';
        // echo \Config::get('auth.role_number.gt_editor')==100; echo '#';
        // echo \Config::get('auth.role_number.gt_guest')==1000; echo '#';
        // echo \Config::get('auth.default_value.password.sysadmin')=='password'; echo '#';
        // echo \Config::get('view.ipp')==50; echo '#';
        // die();
        // 設定ファイル変更後は、 $php artisan config:cache $php artisan serve

        Log::info('HomeController::index()');

        $ipp = \Config::get('view.ipp');
        $items = LaravelPlace::orderBy('id', 'asc')->simplePaginate($ipp);

        $auth_user = Auth::user();
        if(isset($auth_user)){
            Log::info('auth_user: '.$auth_user->id);
            return view('home', ['items' => $items, 'user' => $auth_user]);
        }

        Log::info('auth_user: NULL');
        return view('home', ['items' => $items]);
    }
}

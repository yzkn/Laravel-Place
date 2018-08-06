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
        Log::info('HomeController::index()');

        $ipp = 50;
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

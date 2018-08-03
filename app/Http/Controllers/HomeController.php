<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelPlace;

use Illuminate\Support\Facades\Auth; // 認証で使用

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
        $ipp = 50;
        $items = LaravelPlace::orderBy('id', 'asc')->simplePaginate($ipp);

        $auth_user = Auth::user();
        if(isset($auth_user)){
            return view('home', ['items' => $items, 'user' => $auth_user]);
        }
        return view('home', ['items' => $items]);
    }
}

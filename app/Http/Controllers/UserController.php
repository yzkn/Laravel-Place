<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Illuminate\Support\Facades\Auth; // 認証で使用
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\UserRequest; // バリデーションで使用
use Validator; // フォームからPOSTされるデータに対するバリデーションで使用

use Illuminate\Support\Facades\Log; // ログ出力で使用

class UserController extends Controller
{
    public function isSysadmin($auth_user){
        return (\Config::get('auth.role_number.gt_sysadmin') >= $auth_user->role) ? True : False;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('UserController::index()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);
        $items = User::orderBy('id', 'asc')->get();

        if(isset($items)){
            return view('usermanage.index', ['items' => $items, 'user' => $auth_user]);
        }
        return redirect('/user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('UserController::store()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);

        $user = new User();
        if(isset($user)){
            if($this->isSysadmin($auth_user)){ //
                $form = $request->all();
                unset($form['_token']);
                $form['password'] = Hash::make($user->password);
                $user->fill($form)->save();
            } //
        }
        return redirect('/user');
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
        Log::info('UserController::update()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);
        $this->validate($request, User::$rules_update);

        $user = User::find($request->id);
        if(isset($user)){
            if($this->isSysadmin($auth_user)){ //
                $form = $request->all();
                unset($form['_token']);
                if(!isset($form['password']))
                {
                    $form['password'] = $user->password;
                }

                if($user->role===1 && $form['role'] !== '1')
                {
                    $form['role'] = 1; // 開発者権限からは変更させない
                }
                if($user->role!==1 && $form['role'] === '1')
                {
                    $form['role'] = $user->role; // 開発者権限へは変更させない
                }

                $user->fill($form)->save();
            } //
        }
        return redirect('/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('UserController::destroy()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);

        $user = User::find($id);
        if(isset($user)){
            if($this->isSysadmin($auth_user)){
                $user->delete();
            }
        }
        return redirect('/user');
    }
}

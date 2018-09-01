<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelPlace;

use Illuminate\Support\Facades\Auth; // 認証で使用
use Illuminate\Support\Facades\DB; // ページネーションで使用
use App\Http\Requests\PlaceRequest; // バリデーションで使用
use Validator; // フォームからPOSTされるデータに対するバリデーションで使用

use Illuminate\Support\Facades\Log; // ログ出力で使用

class PlaceController extends Controller
{
    public $ipp = 50;

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
        Log::info('PlaceController::index()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return redirect('/login');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('arg: -');

        // シンプルな例
        // $items = LaravelPlace::all();
        // return $items->toArray();

        // ページネーションありの例
        $items = LaravelPlace::orderBy('id', 'asc')->simplePaginate($this->ipp);

        if(isset($items)){
            return view('placemanage.index', ['items' => $items, 'user' => $auth_user]);
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
        Log::info('PlaceController::create()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return redirect('/login');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('arg: -');

        return view('placemanage.place-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Place\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceRequest $request)
    {
        Log::info('PlaceController::store()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return redirect('/login');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('request: '.print_r($request->all(), true));

        $laravel_place = new LaravelPlace();
        $form = $request->all();
        unset($form['_token']);

        $laravel_place->user_id = $auth_user->id;

        $file = $request->file('image');
        if(isset($file)){
            Log::info('image set');
            $laravel_place->imageorig = $file->getClientOriginalName();
            $hashed_name = basename($file->store('public/'.config('file.path'))); // $ php artisan storage:link
            $laravel_place->image = $hashed_name;
            Log::info('filenames: '. $laravel_place->imageorig.'\t'.$laravel_place->image);
        }
        unset($form['image']);

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
        Log::info('PlaceController::show()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
        }
        else {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('id: '.print_r($id, true));

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
        Log::info('PlaceController::edit()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return redirect('/login');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('id: '.print_r($id, true));

        $laravel_place = LaravelPlace::find($id);
        if(isset($laravel_place)){
            if($auth_user->id===$laravel_place->user->id || $this->isSysadmin($auth_user)){ //
                return view('placemanage.place-edit', ['form' => $laravel_place]);
            } //
        }
        return redirect('/place');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceRequest $request, $id)
    {
        Log::info('PlaceController::update()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return redirect('/login');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('request: '.print_r($request->all(), true));
        Log::info('id: '.print_r($id, true));

        $this->validate($request, LaravelPlace::$rules);
        $laravel_place = LaravelPlace::find($request->id);
        if(isset($laravel_place)){
            if($auth_user->id===$laravel_place->user->id || $this->isSysadmin($auth_user)){ //
                $form = $request->all();
                unset($form['_token']);

                $file = $request->file('image');
                if(isset($file)){
                    Log::info('image set');
                    $laravel_place->imageorig = $file->getClientOriginalName();
                    $hashed_name = basename($file->store('public/'.config('file.path'))); // $ php artisan storage:link
                    $laravel_place->image = $hashed_name;
                    Log::info('filenames: '. $laravel_place->imageorig.'\t'.$laravel_place->image);
                }
                if($request->removeImage == TRUE){
                    Log::info('removeImage: TRUE');
                    $laravel_place->image = NULL;
                    $laravel_place->imageorig = NULL;
                }
                Log::info('unset image,removeImage');
                unset($form['image']);
                unset($form['removeImage']);

                Log::info('fill and save');
                $laravel_place->fill($form)->save();
            } //
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
        Log::info('PlaceController::destroy()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return redirect('/login');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('id: '.print_r($id, true));

        $laravel_place = LaravelPlace::find($id);
        if(isset($laravel_place)){
            if($auth_user->id===$laravel_place->user->id || $this->isSysadmin($auth_user)){ //
                $laravel_place->delete();
            } //
        }
        return redirect('/place');
    }

    /**
     * Find  the specified resource from storage.
     *
     * @param Place\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        Log::info('PlaceController::search()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('request: '.print_r($request->all(), true));
        $param = ['desc'=>'', 'items'=>NULL, 'user' => $auth_user];
        return view('placemanage.search', $param);
    }

    public function where(Request $request)
    {
        Log::info('PlaceController::where()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('request: '.print_r($request->all(), true));

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

        $place = new LaravelPlace();
        if($request->has('desc')){
            $place = $place->orWhere('desc','like','%'.$request->desc.'%');
        }
        $items = $place
            ->orderBy('id', 'asc')
            ->simplePaginate($this->ipp)
            ->appends($request->only(['desc']));
        $param = ['desc'=>$request->desc, 'items'=>$items];
        return view('placemanage.search', $param);
    }
}

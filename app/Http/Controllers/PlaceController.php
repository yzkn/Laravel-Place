<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelPlace;

use Illuminate\Support\Facades\Auth; // 認証で使用
use Illuminate\Support\Facades\DB; // ページネーションで使用
use App\Http\Requests\PlaceRequest; // バリデーションで使用

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth_user = Auth::user();

        // シンプルな例
        // $items = LaravelPlace::all();
        // return $items->toArray();

        // ページネーションありの例
        $ipp = 5;
        $items = LaravelPlace::orderBy('id', 'asc')->simplePaginate($ipp);

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
     * @param  PlaceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceRequest $request, $id)
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

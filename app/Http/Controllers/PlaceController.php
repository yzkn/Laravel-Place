<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Place;
use App\PlacePhoto;

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
        // $items = Place::all();
        // return $items->toArray();

        // ページネーションありの例
        $items = Place::orderBy('id', 'asc')->simplePaginate($this->ipp);

        if(isset($items)){
            return view('placemanage.index', ['items' => $items, 'user' => $auth_user, 'isSysadmin' => $this->isSysadmin($auth_user)]);
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

        $laravel_place = new Place();
        $form = $request->all();
        unset($form['_token']);

        $laravel_place->user_id = $auth_user->id;

        $files = $request->file('image');
        unset($form['image']);
        $laravel_place->fill($form)->save();
        Log::info('laravel_place: '.print_r($laravel_place, true));

        if(isset($files)){
            foreach($files as $file){
                Log::info('image set');
                $photo = new PlacePhoto();
                $photo->place_id =  $laravel_place->id;
                $photo->imageorig = $file->getClientOriginalName();
                $hashed_name = basename($file->store('public/'.config('file.path'))); // $ php artisan storage:link
                $photo->image = $hashed_name;
                $photo->save();
                Log::info('filenames: '. $photo->imageorig.'\t'.$photo->image);
                Log::info('photo: '.print_r($photo, true));
            }
        }

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

        $laravel_place = Place::find($id);
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

        $laravel_place = Place::find($id);
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

        $this->validate($request, Place::$rules);
        $laravel_place = Place::find($request->id);
        if(isset($laravel_place)){
            if($auth_user->id===$laravel_place->user->id || $this->isSysadmin($auth_user)){ //
                $form = $request->all();
                unset($form['_token']);

                $files = $request->file('image');
                if(isset($files)){
                    foreach($files as $file){
                        Log::info('image set');
                        $photo = new PlacePhoto();
                        $photo->place_id =  $laravel_place->id;
                        $photo->imageorig = $file->getClientOriginalName();
                        $hashed_name = basename($file->store('public/'.config('file.path'))); // $ php artisan storage:link
                        $photo->image = $hashed_name;
                        $photo->save();
                        Log::info('filenames: '. $photo->imageorig.'\t'.$photo->image);
                        Log::info('photo: '.print_r($photo, true));
                    }
                }

                if(isset($request->removeImage)){
                    Log::info('removeImage: TRUE');
                    foreach($request->removeImage as $i){
                        PlacePhoto::where('place_id', '=', $laravel_place->id)->where('image', '=', $i)->delete();
                        // 画像ファイルの実体は証跡として残す
                    }
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

        $laravel_place = Place::find($id);
        if(isset($laravel_place)){
            if($auth_user->id===$laravel_place->user->id || $this->isSysadmin($auth_user)){ //
                PlacePhoto::where('place_id', '=', $laravel_place->id)->delete();
                // 画像ファイルの実体は証跡として残す

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
        $param = ['desc'=>'', 'lat'=>'', 'lng'=>'', 'items'=>NULL, 'user' => $auth_user];
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
            [
                'desc' => 'required_without:lat,lng',
                'lat' => 'required_without:desc',
                'lng' => 'required_without:desc'
            ]
        );
        if($validator->fails())
        {
            return redirect('/search')
                ->withErrors($validator)
                ->withInput();
        }

        $place = new Place();
        if(($request->has('desc')) && (strlen($request->desc) > 0)){
            Log::info('desc: '.print_r($request->desk, true));
            $place = $place->orWhere('desc','like','%'.$request->desc.'%');
        }
        if(($request->has('lat'))&&($request->has('lng'))){
            Log::info('request: '.print_r($request->all(), true));
            $place = $place->selectRaw(
                ' * , ' .
                ' SQRT( ' .
                '    POWER( COS( RADIANS( ' .
                ' ? ) ) * 6378.137 * RADIANS( lng - ' . // ?=lat
                ' ? ) , 2 ) + POWER( 6378.137 * RADIANS( lat - ' . // ?=lng
                ' ? ) , 2 ) ' . // ?=lat
                ' ) as dist '
                ,
                [
                    (double)($request->lat),
                    (double)($request->lng),
                    (double)($request->lat)
                ]
            )
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->orderBy('dist', 'asc');
            Log::info('request: '.print_r( [
                (double)($request->lat),
                (double)($request->lng),
                (double)($request->lat)
            ], true));

            Log::info('generated query: '.$place->toSql());
            Log::info('set: '.$place->get());
        } else {
            $place = $place->orderBy('id', 'asc');
            Log::info('order by: id asc');
            Log::info('generated query: '.$place->toSql());
            Log::info('set: '.$place->get());
        }
        $items = $place
            ->simplePaginate($this->ipp)
            ->appends($request->only(['desc', 'lat', 'lng']))
            ;
        $param = ['desc'=>$request->desc, 'lat'=>$request->lat, 'lng'=>$request->lng, 'items'=>$items];
        return view('placemanage.search', $param);
    }
}

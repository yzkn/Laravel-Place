<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaravelPlace extends Model
{
    protected $table = 'laravel-place';
    protected $guarded = array('id');

    public static $rules = array(
        'id' => 'required',
        'lat' => 'numeric',
        'lng' => 'numeric'
    );

    public function getData()
    {
        return $this->id . "\t" . $this;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getUserName(){
        if($this->user !== NULL)
        {
            if($this->user->name !== NULL)
            {
                return $this->user->name;
            }
        }
        return '';
    }

    public function images()
    {
        return $this->hasMany('App\LaravelPlacePhoto', 'id');
    }
}

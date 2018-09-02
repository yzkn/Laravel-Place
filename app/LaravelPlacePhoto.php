<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaravelPlacePhoto extends Model
{
    protected $table = 'laravel-place-photo';
    protected $guarded = array('id');

    public static $rules = array(
        'id' => 'required'
    );

    protected $fillable = ['item_id', 'image', 'imageorig'];

    public function item()
    {
        return $this->belongsTo('App\LaravelPlace');
    }
}

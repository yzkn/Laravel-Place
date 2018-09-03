<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlacePhoto extends Model
{
    protected $table = 'place_photos';
    protected $guarded = array('id');

    public static $rules = array(
        'id' => 'required'
    );

    protected $fillable = ['item_id', 'image', 'imageorig'];

    public function item()
    {
        return $this->belongsTo('App\Place');
    }
}

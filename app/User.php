<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public static $rules_update = array(
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        //'password' => 'string',
        'role' => 'required|numeric|max:1000',
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function places()
    {
        return $this->hasMany('App\LaravelPlace');
    }
}

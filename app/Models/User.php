<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';
    public $table = 'users';

    protected $guarded=[];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'password_salt',
    ];

    public function Country()
    {
        return $this->belongsTo('App\CountriesManagement','country','id');
    }

    public function State()
    {
        return $this->belongsTo('App\StateManagements','state','id');
    }
    public function user_type()
    {
        return $this->belongsTo('App\Roles','user_type','id');
    }

    public function City()
    {
        return $this->belongsTo('App\CityManagement','city','id');
    }

    public function SQuestion()
    {
        return $this->belongsTo('App\loginSecurity','question','id');
    }
}

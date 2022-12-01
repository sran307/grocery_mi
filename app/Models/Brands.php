<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $primaryKey = 'id';
    public $table = 'brands';
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function Country()
    {
        return $this->belongsTo('App\CountriesManagement','country','id');
    }

    public function State()
    {
        return $this->belongsTo('App\StateManagements','state','id');
    }

    public function City()
    {
        return $this->belongsTo('App\CityManagement','city','id');
    }
}

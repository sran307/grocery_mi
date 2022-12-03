<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'email_settings';
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
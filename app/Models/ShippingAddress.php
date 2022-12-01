<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $primaryKey = 'id';
    public $table = 'shipping_addresses';
    protected $hidden = [
        'created_at','updated_at'
    ];
    
    protected $guarded=[];

    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

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

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $primaryKey = 'id';
    public $table = 'shipments';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function ShipOrder()
    {
        return $this->belongsTo('App\Orders','order_id','id');
    }
}

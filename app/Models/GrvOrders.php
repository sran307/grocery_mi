<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrvOrders extends Model
{
    protected $primaryKey = 'id';
    public $table = 'grv_orders';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Orders()
    {
        return $this->belongsTo('App\Orders','order_id','id');
    }

    public function ReOrders()
    {
        return $this->belongsTo('App\ReturnOrder','return_order_id','id');
    }
}

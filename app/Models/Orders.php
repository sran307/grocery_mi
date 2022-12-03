<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $primaryKey = 'id';
    public $table = 'orders';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $guarded=[];
    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function Reference()
    {
        return $this->belongsTo('App\Orders','ref_order_id','id');
    }

    public function GRV()
    {
        return $this->belongsTo('App\GrvOrders','grv_id','id');
    }
}

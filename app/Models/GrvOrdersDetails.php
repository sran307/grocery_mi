<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GrvOrdersDetails extends Model
{
    protected $primaryKey = 'id';
    public $table = 'grv_orders_details';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function GRV()
    {
        return $this->belongsTo('App\GrvOrders','grv_id','id');
    }

    public function ReOrderDets()
    {
        return $this->belongsTo('App\ReturnOrderDetails','rtn_odr_det_id','id');
    }

    public function Products()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function AttName()
    {
        return $this->belongsTo('App\AttributesFields','att_name','id');
    }

    public function AttValue()
    {
        return $this->belongsTo('App\AttributesSettings','att_value','id');
    }
}

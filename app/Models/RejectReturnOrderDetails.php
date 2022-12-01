<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejectReturnOrderDetails extends Model
{
    protected $primaryKey = 'id';
    public $table = 'reject_return_order_details';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function ReOrders()
    {
        return $this->belongsTo('App\ReturnOrder','return_order_id','id');
    }

    public function ReOrdersDets()
    {
        return $this->belongsTo('App\OrderDetails','rtn_odr_det_id','id');
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

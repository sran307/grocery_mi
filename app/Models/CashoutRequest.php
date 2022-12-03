<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CashoutRequest extends Model
{
    protected $primaryKey = 'id';
    public $table = 'cashout_requests';
    protected $hidden = [
        'updated_at'
    ];

    public function Cashouts()
    {
        return $this->belongsTo('App\Cashout','request_code','id');
    }

    public function CashOrders()
    {
        return $this->belongsTo('App\Orders','order_code','id');
    }

    public function CashOdrDets()
    {
        return $this->belongsTo('App\OrderDetails','order_dets','id');
    }

    public function CashProducts()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function CashMerchants()
    {
        return $this->belongsTo('App\User','merchant_id','id');
    }

    public function CNotes()
    {
        return $this->belongsTo('App\CreditsNotes','cn_id','id');
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

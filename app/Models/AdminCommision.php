<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminCommision extends Model
{
    protected $primaryKey = 'id';
    public $table = 'admin_commisions';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function ComisOrders()
    {
        return $this->belongsTo('App\Orders','order_code','order_code');
    }

    public function ComisOdrDets()
    {
        return $this->belongsTo('App\OrderDetails','order_dets','id');
    }

    public function CNotes()
    {
        return $this->belongsTo('App\CreditsNotes','cn_id','id');
    }

    public function ComisProducts()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function ComisMerchant()
    {
        return $this->belongsTo('App\User','merchant_id','id');
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

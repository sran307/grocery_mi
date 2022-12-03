<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OfferTransaction extends Model
{
    protected $primaryKey = 'id';
    public $table = 'offer_transactions';
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function OfferOrders()
    {
        return $this->belongsTo('App\Orders','order_code','order_code');
    }

    public function Offers()
    {
        return $this->belongsTo('App\Offers','offer','id');
    }

    public function OffersSubs()
    {
        return $this->belongsTo('App\OffersSub','offer_det_id','id');
    }

    public function OfferProducts()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }
    
    public function AttributeName()
    {
        return $this->belongsTo('App\AttributesFields','att_name','id');
    }

    public function AttributeValue()
    {
        return $this->belongsTo('App\AttributesSettings','att_value','id');
    }
}

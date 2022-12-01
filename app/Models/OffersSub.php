<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffersSub extends Model
{
    protected $primaryKey = 'id';
    public $table = 'offers_subs';
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function Offers()
    {
        return $this->belongsTo('App\Offers','offer','id');
    }

    public function OfferProducts()
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

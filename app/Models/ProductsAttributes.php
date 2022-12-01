<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsAttributes extends Model
{
    protected $primaryKey = 'id';
    public $table = 'products_attributes';
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function AttributeName()
    {
        return $this->belongsTo('App\AttributesFields','attribute_name','id');
    }

    public function AttributeValue()
    {
        return $this->belongsTo('App\AttributesSettings','attribute_values','id');
    }

    public function Colors()
    {
        return $this->belongsTo('App\ColorSettings','colors','id');
    }

    public function Sizes()
    {
        return $this->belongsTo('App\SizeSettings','sizes','id');
    }

    public function Capacity()
    {
        return $this->belongsTo('App\CapacitySettings','capacity','id');
    }
}

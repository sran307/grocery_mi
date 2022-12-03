<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SubStock extends Model
{
    protected $primaryKey = 'id';
    public $table = 'sub_stocks';
    protected $hidden = [
        'updated_at'
    ];

    public function Products()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function Attribute()
    {
        return $this->belongsTo('App\ProductsAttributes','attribute','id');
    }

    public function Stock()
    {
        return $this->belongsTo('App\StockManagement','stock','id');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockManagement extends Model
{
    protected $primaryKey = 'id';
    public $table = 'stock_managements';
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function Products()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function Creatiers()
    {
        return $this->belongsTo('App\User','created_user','id');
    }
}

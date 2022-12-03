<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StockTransactions extends Model
{
    protected $primaryKey = 'id';
    public $table = 'stock_transactions';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function StockOrders()
    {
        return $this->belongsTo('App\Orders','order_code','order_code');
    }

    public function StockProducts()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function StockUser()
    {
        return $this->hasmany('App\User');
    }

    public function StockAttName()
    {
        return $this->belongsTo('App\AttributesFields','att_name','id');
    }

    public function StockAttValue()
    {
        return $this->belongsTo('App\AttributesSettings','att_value','id');
    }
}

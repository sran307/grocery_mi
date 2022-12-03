<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrdersTransactions extends Model
{
    protected $primaryKey = 'id';
    public $table = 'orders_transactions';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}

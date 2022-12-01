<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    protected $primaryKey = 'id';
    public $table = 'cashouts';
    protected $hidden = [
        'updated_at'
    ];

    public function CashMerchants()
    {
        return $this->belongsTo('App\User','merchant_id','id');
    }
}

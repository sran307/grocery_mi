<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashoutPayment extends Model
{
    protected $primaryKey = 'id';
    public $table = 'cashout_payments';
    protected $hidden = [
        'updated_at'
    ];

    public function Cashouts()
    {
        return $this->belongsTo('App\Cashout','request_code','id');
    }

    public function CashoutBank()
    {
        return $this->belongsTo('App\BankDetails','bank','id');
    }
}

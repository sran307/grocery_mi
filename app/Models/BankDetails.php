<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    protected $primaryKey = 'id';
    public $table = 'bank_details';
    protected $hidden = [
        'updated_at'
    ];

    public function Merchants()
    {
        return $this->belongsTo('App\User','merchant_id','id');
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminCashouts extends Model
{
    protected $primaryKey = 'id';
    public $table = 'admin_cashouts';
    protected $hidden = [
        'updated_at'
    ];

    public function Vendors()
    {
        return $this->belongsTo('App\User','vendor','id');
    }

    public function CNotes()
    {
        return $this->belongsTo('App\CreditsNotes','credit_note','id');
    }

    public function Order()
    {
        return $this->belongsTo('App\Orders','order_id','id');
    }
}

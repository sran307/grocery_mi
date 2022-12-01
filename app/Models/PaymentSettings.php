<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'payment_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

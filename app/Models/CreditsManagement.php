<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CreditsManagement extends Model
{
    protected $primaryKey = 'id';
    public $table = 'credits_managements';
    protected $hidden = [
        'updated_at'
    ];

    public function Merchants()
    {
        return $this->belongsTo('App\User','merchant_id','id');
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AccountSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'account_settings';
    protected $hidden = [
        'updated_at'
    ];

    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}

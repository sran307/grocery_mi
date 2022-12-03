<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $primaryKey = 'id';
    public $table = 'user_addresses';
    protected $hidden = [
        'updated_at'
    ];

    
}

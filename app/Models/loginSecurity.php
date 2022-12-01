<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loginSecurity extends Model
{
    protected $primaryKey = 'id';
    public $table = 'login_securities';
    protected $hidden = [
        'updated_at'
    ];
}

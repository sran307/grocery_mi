<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class StateManagements extends Model
{
    protected $primaryKey = 'id';
    public $table = 'state_managements';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cod extends Model
{
    protected $primaryKey = 'id';
    public $table = 'cod';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

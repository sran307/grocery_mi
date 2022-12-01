<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    protected $primaryKey = 'id';
    public $table = 'modules';
    protected $hidden = [
        'updated_at'
    ];
}

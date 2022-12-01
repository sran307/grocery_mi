<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $primaryKey = 'id';
    public $table = 'stores';
    protected $hidden = [
        'updated_at'
    ];
}

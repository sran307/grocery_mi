<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $primaryKey = 'id';
    public $table = 'stores';
    protected $hidden = [
        'updated_at'
    ];
}

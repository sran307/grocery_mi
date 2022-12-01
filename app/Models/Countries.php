<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $primaryKey = 'ID';
    public $table = 'countries';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

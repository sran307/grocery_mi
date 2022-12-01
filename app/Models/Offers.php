<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $primaryKey = 'id';
    public $table = 'offers';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

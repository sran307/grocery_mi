<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Disclaimers extends Model
{
    protected $primaryKey = 'id';
    public $table = 'disclaimers';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

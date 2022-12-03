<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Previlages extends Model
{
    protected $primaryKey = 'id';
    public $table = 'previlages';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

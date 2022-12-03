<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $primaryKey = 'id';
    public $table = 'widgets';
    protected $hidden = [
        'updated_at'
    ];
}

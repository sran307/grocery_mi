<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $primaryKey = 'id';
    public $table = 'widgets';
    protected $hidden = [
        'updated_at'
    ];
}

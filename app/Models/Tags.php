<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $primaryKey = 'id';
    public $table = 'tags';
    protected $hidden = [
        'updated_at'
    ];
}

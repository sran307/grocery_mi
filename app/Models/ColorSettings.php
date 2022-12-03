<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ColorSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'color_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

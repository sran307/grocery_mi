<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class NoimageSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'noimage_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

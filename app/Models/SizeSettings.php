<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'size_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

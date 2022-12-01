<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogoSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'logo_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

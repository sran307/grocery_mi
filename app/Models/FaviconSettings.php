<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaviconSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'favicon_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

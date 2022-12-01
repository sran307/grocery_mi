<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapacitySettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'capacity_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'general_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BuildPcSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'build_pc_settings';
    protected $hidden = [
        'updated_at'
    ];

    
}

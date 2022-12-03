<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PackageDimension extends Model
{
    protected $primaryKey = 'id';
    public $table = 'package_dimensions';
    protected $hidden = [
        'updated_at'
    ];

    
}

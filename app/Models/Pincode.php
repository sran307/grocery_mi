<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $primaryKey = 'id';
    public $table = 'pincodes';
    protected $hidden = [
        'updated_at'
    ];

    
}

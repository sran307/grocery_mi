<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $primaryKey = 'id';
    public $table = 'pincodes';
    protected $hidden = [
        'updated_at'
    ];

    
}

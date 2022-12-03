<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'id';
    public $table = 'notifications';
    protected $hidden = [
        'updated_at'
    ];

    
}

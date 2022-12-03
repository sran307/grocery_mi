<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $primaryKey = 'id';
    public $table = 'contacts';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

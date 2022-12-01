<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountriesManagement extends Model
{
    protected $primaryKey = 'id';
    public $table = 'countries_managements';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

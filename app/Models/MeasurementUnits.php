<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MeasurementUnits extends Model
{
    protected $primaryKey = 'id';
    public $table = 'measurement_units';
    protected $hidden = [
        'updated_at'
    ];
}

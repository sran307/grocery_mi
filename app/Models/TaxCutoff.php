<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TaxCutoff extends Model
{
    protected $primaryKey = 'id';
    public $table = 'tax_cutoffs';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

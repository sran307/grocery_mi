<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AttributesSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'attributes_settings';
    protected $hidden = [
        'updated_at','created_at'
    ];

    public function AttributesFields()
    {
        return $this->belongsTo('App\AttributesFields','att_name','id');
    }
}

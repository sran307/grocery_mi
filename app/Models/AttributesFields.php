<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AttributesFields extends Model
{
    protected $primaryKey = 'id';
    public $table = 'attributes_fields';
    protected $hidden = [
        'updated_at','created_at'
    ];
    
     public function AttributesValues1()
    {
        return $this->hasMany('App\AttributesSettings','att_name','id');
    }
     public function AttributesValues() {
        return $this->AttributesValues1()->where('is_block','=', 1);
    }
}

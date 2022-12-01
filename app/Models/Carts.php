<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $primaryKey = 'id';
    public $table = 'carts';
    protected $hidden = [
        'updated_at','created_at'
    ];

    protected $guarded=[];

    public function Products()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function AttName()
    {
        return $this->belongsTo('App\AttributesFields','att_name','id');
    }

    public function AttValue()
    {
        return $this->belongsTo('App\AttributesSettings','att_value','id');
    }
}

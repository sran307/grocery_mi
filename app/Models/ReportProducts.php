<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ReportProducts extends Model
{
    protected $primaryKey = 'id';
    public $table = 'report_products';
    protected $hidden = [
        'created_at','updated_at'
    ];
 public function Orders()
    {
        return $this->belongsTo('App\Orders','order_id','id');
    }
     public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

   
}

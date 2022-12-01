<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatedProducts extends Model
{
    protected $primaryKey = 'id';
    public $table = 'related_products';
    protected $hidden = [
        'updated_at'
    ];

    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $primaryKey = 'id';
    public $table = 'wish_lists';
    protected $hidden = [
        'updated_at','created_at'
    ];

    public function Products()
    {
        return $this->belongsTo('App\Products','product_id','id');
    }

    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}

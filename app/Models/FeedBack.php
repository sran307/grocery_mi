<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    protected $primaryKey = 'id';
    public $table = 'feed_backs';
    protected $hidden = [
        'updated_at'
    ];

    public function Customer()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'site_settings';
    protected $hidden = [
        'updated_at'
    ];

    public function Vendors()
    {
        return $this->belongsTo('App\User','vendor','id');
    }

   
}

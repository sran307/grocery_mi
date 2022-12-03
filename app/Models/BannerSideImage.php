<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BannerSideImage extends Model
{
    protected $primaryKey = 'id';
    public $table = 'banner_side_images';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerImageSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'banner_image_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CategoryBannerSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'category_banner_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CategoryAdvertisementSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'category_advertisement_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

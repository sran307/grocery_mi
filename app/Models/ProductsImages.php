<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductsImages extends Model
{
    protected $primaryKey = 'id';
    public $table = 'products_images';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

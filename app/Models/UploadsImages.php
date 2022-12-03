<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UploadsImages extends Model
{
    protected $primaryKey = 'id';
    public $table = 'uploads_images';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

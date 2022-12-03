<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $primaryKey = 'id';
    public $table = 'news_letters';
    protected $hidden = [
        'created_at','updated_at'
    ];
}

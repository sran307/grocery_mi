<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMediaSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'social_media_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}